<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Costume;
use App\Models\Rental;
use App\Models\Message; // เปลี่ยนจาก ChatMessage เป็น Message
use App\Models\CostumeVariant;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        // ดึงรายการเช่าที่รอการยืนยัน (status = 'pending')
        $pendingRentals = Rental::where('status', 'pending')
                                ->with(['user', 'costume'])
                                ->get();

        // ดึงรายการเช่าที่ถูกยกเลิก (ถ้ามีฟิลด์ deleted_at หรือ status = 'cancelled')
        $cancelledRentals = Rental::where('status', 'cancelled')
                                  ->with(['user', 'costume'])
                                  ->get();

        // ส่งข้อมูลไปยัง View
        return view('admin.rentals', compact('pendingRentals', 'cancelledRentals'));
    }

    public function dashboard()
    {
        $costumes = Costume::with('rentals')->get();
        $categories = Category::all();
        $rentals = Rental::with(['user', 'costume'])->get(); // ดึงข้อมูลการเช่าทั้งหมด
        return view('admin.dashboard', compact('costumes', 'categories', 'rentals'));
    }

    public function completeRental($id)
    {
        $rental = Rental::with('costume')->findOrFail($id);
        
        if ($rental->status !== 'active') {
            return redirect()->back()->with('error', 'ไม่สามารถคืนได้: รายการนี้ไม่ใช่สถานะใช้งาน');
        }

        $rental->update(['status' => 'completed']);
        $costume = $rental->costume;
        $oldStock = $costume->stock;
        $costume->increment('stock', $rental->quantity);
        \Log::info('Stock Updated:', [
            'costume_id' => $costume->id,
            'old_stock' => $oldStock,
            'new_stock' => $costume->stock,
            'quantity_returned' => $rental->quantity
        ]);

        return redirect()->back()->with('success', 'คืนสินค้าสำเร็จ สต็อกอัปเดตแล้ว');
    }

    public function storeCostume(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'colors' => 'required|array',
                'colors.*.color' => 'required|string|max:50',
                'colors.*.stock' => 'required|integer|min:0',
                'sizes' => 'required|array',
                'sizes.*.size' => 'required|string|max:10',
                'sizes.*.stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
            ]);

            // คำนวณ stock รวม
            $totalStock = max(
                array_sum(array_column($request->colors, 'stock')),
                array_sum(array_column($request->sizes, 'stock'))
            );

            // สร้างชุด (ไม่รวมรูปภาพในตาราง costumes)
            $costume = Costume::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'stock' => $totalStock,
                'description' => $request->description,
            ]);

            // บันทึกรูปภาพทั้งหมด
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $path = $image->store('costumes', 'public');
                    
                    \App\Models\CostumeImage::create([
                        'costume_id' => $costume->id,
                        'image_path' => $path,
                        'is_primary' => $key === 0 // ตั้งค่ารูปแรกเป็นรูปหลัก
                    ]);
                }
            }

            // บันทึก Colors
            foreach ($request->colors as $colorData) {
                \App\Models\CostumeVariant::create([
                    'costume_id' => $costume->id,
                    'type' => 'color',
                    'value' => $colorData['color'],
                    'stock' => $colorData['stock'],
                ]);
            }

            // บันทึก Sizes
            foreach ($request->sizes as $sizeData) {
                \App\Models\CostumeVariant::create([
                    'costume_id' => $costume->id,
                    'type' => 'size',
                    'value' => $sizeData['size'],
                    'stock' => $sizeData['stock'],
                ]);
            }

            return redirect()->route('admin.dashboard')->with('success', 'เพิ่มชุดสำเร็จ!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function createCostume()
    {
        $categories = Category::all();
        return view('admin.costume.create', compact('categories'));
    }

    public function rentals()
    {
        $rentals = Rental::with(['user', 'costume'])->where('status', 'pending')->get();
        \Log::info('Pending Rentals:', [
            'count' => $rentals->count(),
            'data' => $rentals->toArray()
        ]);
        if ($rentals->isEmpty()) {
            \Log::warning('No pending rentals found');
        }
        return view('admin.rentals', compact('rentals'));
    }

    public function confirmRental($id)
    {
        try {
            $rental = Rental::with('costume')->findOrFail($id);
            $costume = $rental->costume;

            if ($rental->status !== 'pending') {
                return response()->json(['success' => false, 'error' => 'คำสั่งซื้อนี้ไม่สามารถยืนยันได้'], 400);
            }

            if ($costume->stock < $rental->quantity) {
                return response()->json(['success' => false, 'error' => 'สต็อกไม่เพียงพอ'], 400);
            }

            // ลดสต็อกของ variant (สีและไซส์)
            if ($rental->color_id) {
                $colorVariant = \App\Models\CostumeVariant::find($rental->color_id);
                if ($colorVariant && $colorVariant->stock >= $rental->quantity) {
                    $colorVariant->decrement('stock', $rental->quantity);
                } else {
                    return response()->json(['success' => false, 'error' => 'สต็อกสีไม่เพียงพอ'], 400);
                }
            }
            if ($rental->size_id) {
                $sizeVariant = \App\Models\CostumeVariant::find($rental->size_id);
                if ($sizeVariant && $sizeVariant->stock >= $rental->quantity) {
                    $sizeVariant->decrement('stock', $rental->quantity);
                } else {
                    return response()->json(['success' => false, 'error' => 'สต็อกไซส์ไม่เพียงพอ'], 400);
                }
            }

            $rental->update(['status' => 'active']);
            $costume->decrement('stock', $rental->quantity);

            return response()->json(['success' => true, 'message' => 'ยืนยันคำสั่งซื้อสำเร็จ']);
        } catch (\Exception $e) {
            \Log::error('Error confirming rental:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'เกิดข้อผิดพลาดในระบบ'], 500);
        }
    }

    public function chats()
    {
        $rentalsWithMessages = Rental::whereHas('messages')
            ->with(['user', 'costume', 'messages' => function ($query) {
                $query->latest()->limit(1); // ดึงข้อความล่าสุด
            }])
            ->withCount(['messages as unread_messages' => function ($query) {
                $query->where('is_admin', false) // ข้อความจากผู้ใช้
                      ->where('is_read', false);  // ที่ยังไม่ได้อ่าน
            }])
            ->get();

        $rental = null;
        $messages = null;

        return view('admin.chat', compact('rentalsWithMessages', 'rental', 'messages'));
    }

    public function chat($rentalId)
    {
        $rental = Rental::with(['user', 'costume'])->findOrFail($rentalId);
        $messages = Message::where('rental_id', $rentalId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // อัปเดตสถานะข้อความเป็น "อ่านแล้ว" เมื่อแอดมินเข้ามาดู
        Message::where('rental_id', $rentalId)
            ->where('is_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $rentalsWithMessages = null;

        return view('admin.chat', compact('rental', 'messages', 'rentalsWithMessages'));
    }

    public function show($id)
    {
        $costume = Costume::with('colors')->findOrFail($id);
        return view('costume.show', compact('costume'));
    }
    public function getMessages($rentalId)
    {
        $messages = Message::where('rental_id', $rentalId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
        ]);
    }
    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'rental_id' => $request->rental_id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => $request->is_admin ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message_id' => $message->id,
        ]);
    }
    public function confirmRentalFromChat($rentalId)
    {
        try {
            $rental = Rental::with('costume')->findOrFail($rentalId);

            // ตรวจสอบสถานะ
            if ($rental->status !== 'pending') {
                return response()->json(['success' => false, 'error' => 'คำสั่งนี้ไม่สามารถยืนยันได้'], 400);
            }

            // ตรวจสอบสต็อก
            if ($rental->costume->stock < $rental->quantity) {
                return response()->json(['success' => false, 'error' => 'สต็อกไม่เพียงพอ'], 400);
            }

            // อัปเดตสถานะเป็น active และลดสต็อก
            $rental->update(['status' => 'active']);
            $rental->costume->decrement('stock', $rental->quantity);

            // ส่งข้อความอัตโนมัติ
            $confirmationMessage = "ยืนยันคำสั่งซื้อ ทางเราจะทำการจัดส่งให้\n" .
                "โปรดอ่านข้อตกลง:\n" .
                "1. หากชุดมีความเสียหายจะมีค่าปรับเพิ่ม\n" .
                "2. หากไม่ยอมจัดส่งคืนเมื่อเกินสัญญาครบ 3 วัน จะไม่ได้ค่ามัดจำคืนและถูกดำเนินคดี\n" .
                "3. เมื่อจัดส่งแล้วโปรดแจ้งหลักฐานการจัดส่ง เมื่อสินค้ามาถึงทางเราจะคืนค่ามัดจำและค่าจัดส่งให้ท่าน\n" .
                "ขอบคุณที่ใช้บริการ";

            $message = Message::create([
                'rental_id' => $rental->id,
                'user_id' => auth()->id(), // ID ของแอดมิน
                'message' => $confirmationMessage,
                'is_admin' => true,
            ]);

            return response()->json([
                'success' => true,
                'message_id' => $message->id,
                'message' => $confirmationMessage,
                'created_at' => $message->created_at->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error confirming rental from chat:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()], 500);
        }
    }
}