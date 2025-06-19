<?php
namespace App\Http\Controllers;

use App\Models\Costume;
use App\Models\Rental;
use App\Models\Message;
use App\Models\ChatMessage;
use App\Models\CostumeVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CostumeController extends Controller
{
    public function show($id)
    {
        $costume = Costume::with(['colors' => function ($query) {
            $query->where('type', 'color');
        }, 'sizes' => function ($query) {
            $query->where('type', 'size');
        }, 'rentals' => function ($query) {
            $query->where('status', 'active');
        }])->findOrFail($id);

        return view('costume.show', compact('costume'));
    }

    public function rent($id)
    {
        $costume = Costume::with('rentals')->findOrFail($id);
        return view('costume.rent', compact('costume'));
    }

    public function checkout(Request $request)
    {
        \Log::info('Checkout accessed', $request->all());

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'กรุณาล็อกอินก่อนเช่าชุด');
        }

        $data = $request->validate([
            'costume_id' => 'required|exists:costumes,id',
            'color_id' => 'required|exists:costume_variants,id',
            'size_id' => 'required|exists:costume_variants,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1',
        ]);

        $costume = Costume::findOrFail($data['costume_id']);
        $colorVariant = CostumeVariant::findOrFail($data['color_id']);
        $sizeVariant = CostumeVariant::findOrFail($data['size_id']);

        $activeRentalsForColor = $costume->rentals()->where('color_id', $data['color_id'])->where('status', 'active')->sum('quantity');
        $activeRentalsForSize = $costume->rentals()->where('size_id', $data['size_id'])->where('status', 'active')->sum('quantity');
        
        if ($colorVariant->stock - $activeRentalsForColor < $data['quantity'] || $sizeVariant->stock - $activeRentalsForSize < $data['quantity']) {
            \Log::info('Stock check failed', [
                'color_id' => $data['color_id'],
                'color_stock' => $colorVariant->stock,
                'active_color_rentals' => $activeRentalsForColor,
                'size_id' => $data['size_id'],
                'size_stock' => $sizeVariant->stock,
                'active_size_rentals' => $activeRentalsForSize,
                'requested_quantity' => $data['quantity'],
            ]);
            return redirect()->back()->withErrors(['quantity' => 'สต็อกไม่เพียงพอสำหรับสีหรือไซส์ที่เลือก']);
        }

        try {
            $rental = Rental::create([
                'user_id' => auth()->id(),
                'costume_id' => $costume->id,
                'color_id' => $data['color_id'],
                'size_id' => $data['size_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'quantity' => $data['quantity'],
                'status' => 'pending',
            ]);
            \Log::info('Rental created', ['rental_id' => $rental->id]);

            // แทนที่จะ redirect ไป payment ให้ไปหน้า checkout.blade.php
            return view('costume.checkout', [
                'costume' => $costume,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'quantity' => $data['quantity'],
                'rental_id' => $rental->id, // ส่ง rental_id ไปด้วย
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating rental', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->withErrors(['error' => 'เกิดข้อผิดพลาดในการสร้างคำสั่งเช่า']);
        }
    }

    public function payment(Request $request)
    {
        \Log::info('Payment route accessed', [
            'method' => $request->method(),
            'rental_id' => $request->input('rental_id'),
            'session_exists' => session()->exists('_token'),
            'user_authenticated' => auth()->check(),
            'session_data' => session()->all()
        ]);

        if (!auth()->check()) {
            \Log::warning('User not authenticated, redirecting to login');
            return redirect()->route('login')->with('error', 'กรุณาล็อกอินก่อน');
        }

        if ($request->isMethod('post')) {
            // ตรวจสอบว่าเป็น POST จาก checkout หรือ payment
            if ($request->has('address')) {
                // POST จาก checkout.blade.php
                \Log::info('Processing POST request from checkout', $request->all());

                $data = $request->validate([
                    'rental_id' => 'required|exists:rentals,id',
                    'costume_id' => 'required|exists:costumes,id',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_date',
                    'quantity' => 'required|integer|min:1',
                    'address' => 'required|string|max:255',
                    'phone_number' => 'required|string|max:20',
                    'national_id' => 'required|string|max:13',
                ]);

                $user = auth()->user();
                $user->update([
                    'address' => $data['address'],
                    'phone_number' => $data['phone_number'],
                    'national_id' => $data['national_id'],
                ]);

                $rental = Rental::findOrFail($data['rental_id']);
                $costume = Costume::findOrFail($data['costume_id']);

                \Log::info('Rendering payment view after POST', ['rental_id' => $rental->id]);
                return view('costume.payment', [
                    'rental' => $rental,
                    'costume' => $costume,
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'quantity' => $data['quantity'],
                ]);
            } else {
                // POST จาก payment.blade.php (ส่งสลิป)
                \Log::info('Processing POST request from payment (slip upload)', $request->all());

                $data = $request->validate([
                    'rental_id' => 'required|exists:rentals,id',
                    'costume_id' => 'required|exists:costumes,id',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_date',
                    'quantity' => 'required|integer|min:1',
                    'payment_proof' => 'required|image|max:2048',
                ]);

                $rental = Rental::findOrFail($data['rental_id']);
                if ($rental->user_id !== auth()->id()) {
                    \Log::warning('Unauthorized update attempt', ['rental_id' => $rental->id]);
                    return redirect()->route('home')->with('error', 'คุณไม่มีสิทธิ์อัปเดตคำสั่งเช่านี้');
                }

                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
                $rental->update(['payment_proof' => $paymentProofPath]);

                $costume = $rental->costume;
                $start = new \DateTime($rental->start_date);
                $end = new \DateTime($rental->end_date);
                $days = $end->diff($start)->days + 1;
                $totalPrice = $days * $costume->price * $rental->quantity;

                $autoMessage = Message::create([
                    'rental_id' => $rental->id,
                    'user_id' => auth()->id(),
                    'message' => "ผู้ใช้ได้ยืนยันการชำระเงิน\n" .
                                "ชุด: {$costume->name}\n" .
                                "จำนวน: {$rental->quantity} ชุด\n" .
                                "วันที่เริ่ม: {$rental->start_date}\n" .
                                "วันที่สิ้นสุด: {$rental->end_date}\n" .
                                "จำนวนวัน: {$days} วัน\n" .
                                "ราคารวม: ฿" . number_format($totalPrice) . "\n" .
                                "หลักฐานการชำระเงิน: " . asset('storage/' . $paymentProofPath),
                    'is_admin' => false,
                ]);

                \Log::info('Redirecting to chat', ['rental_id' => $rental->id]);
                return redirect()->route('chat', $rental->id)
                    ->with('success', 'ชำระเงินสำเร็จ! กรุณารอแอดมินตรวจสอบ');
            }
        }

        // GET logic
        \Log::info('Processing GET request', ['rental_id' => $request->input('rental_id')]);
        $rentalId = $request->input('rental_id');
        if (!$rentalId) {
            \Log::warning('No rental_id provided in GET request');
            return redirect()->route('home')->with('error', 'ไม่พบข้อมูลการเช่า กรุณาเริ่มต้นใหม่');
        }

        $rental = Rental::with('costume')->find($rentalId);
        if (!$rental) {
            \Log::warning('Rental not found', ['rental_id' => $rentalId]);
            return redirect()->route('home')->with('error', 'ไม่พบคำสั่งเช่านี้ กรุณาเริ่มต้นใหม่');
        }

        if ($rental->user_id !== auth()->id()) {
            \Log::warning('Unauthorized access', ['rental_id' => $rentalId, 'user_id' => auth()->id()]);
            return redirect()->route('home')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงคำสั่งเช่านี้');
        }

        if (!$rental->costume) {
            \Log::error('Costume not found for rental', [
                'rental_id' => $rentalId,
                'costume_id' => $rental->costume_id
            ]);
            return redirect()->route('home')->with('error', 'ไม่พบข้อมูลชุดสำหรับคำสั่งเช่านี้');
        }

        \Log::info('Rendering payment view', ['rental_id' => $rentalId]);
        return view('costume.payment', [
            'rental' => $rental,
            'costume' => $rental->costume,
            'start_date' => $rental->start_date,
            'end_date' => $rental->end_date,
            'quantity' => $rental->quantity,
        ]);
    }

    public function storeRent(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'กรุณาล็อกอินก่อนเช่าชุด');
        }

        $request->validate([
            'costume_id' => 'required|exists:costumes,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'quantity' => 'required|integer|min:1|max:' . Costume::findOrFail($request->costume_id)->stock,
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'national_id' => 'required|string|max:13',
        ]);

        $costume = Costume::findOrFail($request->costume_id);
        if ($costume->stock < $request->quantity || $costume->rentals()->where('status', 'active')->exists()) {
            return redirect()->back()->with('error', 'ชุดนี้ไม่พร้อมให้เช่าตามจำนวนที่เลือก');
        }

        $user = Auth::user();
        $user->update([
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'national_id' => $request->national_id,
        ]);

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'costume_id' => $costume->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'quantity' => $request->quantity,
            'status' => 'active',
        ]);

        $costume->decrement('stock', $request->quantity);

        return redirect()->route('home')->with('success', 'เช่าชุดสำเร็จ!');
    }

    public function featuredIndex()
    {
        $featured_costumes = Costume::where('is_featured', true)
            ->orWhere('featured_at', '<=', now())
            ->paginate(12);

        return view('costumes.featured', compact('featured_costumes'));
    }

    public function sendChatMessage(Request $request)
    {
        try {
            \Log::info('Chat send request:', $request->all());

            $request->validate([
                'rental_id' => 'required|exists:rentals,id',
                'message' => 'required|string',
            ]);

            $message = ChatMessage::create([
                'rental_id' => $request->rental_id,
                'user_id' => Auth::id(),
                'message' => $request->message,
                'is_admin' => $request->input('is_admin', false),
            ]);

            \Log::info('Chat message created:', ['id' => $message->id]);

            return response()->json(['success' => true, 'message' => $message->message]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in chat send: ' . json_encode($e->errors()));
            return response()->json(['success' => false, 'error' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Chat send error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}