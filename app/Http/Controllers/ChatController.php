<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show(Rental $rental)
    {
        // โหลดข้อมูลการเช่าและข้อความ
        $costume = $rental->costume;
        $messages = Message::where('rental_id', $rental->id)->orderBy('created_at', 'asc')->get();
        $start_date = $rental->start_date;
        $end_date = $rental->end_date;

        // ส่งข้อมูลไปยังหน้าแชท
        return view('costume.chat', compact('rental', 'costume', 'messages', 'start_date', 'end_date'));
    }

    // ถ้าต้องการให้ AJAX ดึงข้อความ (ตามหน้า chat.blade.php เดิม)
    public function getMessages($rentalId)
    {
        $messages = Message::where('rental_id', $rentalId)->orderBy('created_at', 'asc')->get();
        return response()->json(['messages' => $messages]);
    }

    // เมธอดสำหรับส่งข้อความ
    public function send(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'rental_id' => $request->rental_id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => false,
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }
}