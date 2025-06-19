<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Message; // เพิ่ม Model Message

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with('costume')
                         ->where('user_id', auth()->id())
                         ->get();
        return view('rentals.index', compact('rentals'));
    }

    public function edit($id)
    {
        $rental = Rental::findOrFail($id);
        return view('rentals.edit', compact('rental'));
    }

    public function update(Request $request, $id)
    {
        $rental = Rental::findOrFail($id);

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $rental->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('rentals.index')->with('success', 'แก้ไขการเช่าสำเร็จ!');
    }

    public function destroy(Request $request, $id)
    {
        $rental = Rental::findOrFail($id);

        if ($rental->user_id !== auth()->id() || $rental->status !== 'pending') {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ยกเลิกคำสั่งนี้');
        }

        $cancelReason = $request->input('cancel_reason');
        $rental->update([
            'cancel_reason' => $cancelReason,
            'status' => 'cancelled',
        ]);

        $rental->delete();

        return redirect()->back()->with('success', 'ยกเลิกการเช่าสำเร็จ!');
    }

    public function adminIndex()
    {
        $pendingRentals = Rental::with('user', 'costume')
                                ->where('status', 'pending')
                                ->get();

        $cancelledRentals = Rental::onlyTrashed()
                                  ->with('user', 'costume')
                                  ->where('status', 'cancelled')
                                  ->get();

        return view('admin.rentals', compact('pendingRentals', 'cancelledRentals'));
    }

    // เพิ่มฟังก์ชันสำหรับหน้าแชทของผู้ใช้
    public function userChat()
    {
        $rental = Rental::where('user_id', auth()->id())->latest()->first();

        if (!$rental) {
            return redirect()->route('rentals.index')->with('error', 'คุณยังไม่มีคำสั่งเช่า กรุณาสร้างคำสั่งเช่าก่อน');
        }

        $messages = Message::where('rental_id', $rental->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.user', compact('rental', 'messages'));
    }

    // เพิ่มฟังก์ชันสำหรับส่งข้อความ
    public function send(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'message' => 'required|string|max:1000',
            'is_admin' => 'required|boolean',
        ]);

        $message = Message::create([
            'rental_id' => $request->rental_id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => $request->is_admin,
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }
}