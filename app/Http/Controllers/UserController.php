<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'nullable|string|max:20|regex:/^[0-9]{9,10}$/',
            'national_id' => 'nullable|string|size:13|regex:/^[0-9]{13}$/',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'national_id' => $request->national_id,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Debug: ดูว่า $data มีค่าอะไรบ้าง
        \Log::info('Data to update:', $data);

        // บันทึกข้อมูลและตรวจสอบผลลัพธ์
        $updated = $user->update($data);

        // Debug: ดูว่า update สำเร็จหรือไม่
        \Log::info('Update result:', ['updated' => $updated]);

        return redirect()->back()->with('success', 'อัปเดตข้อมูลส่วนตัวสำเร็จ!');
    }

    public function orders()
    {
        $rentals = auth()->user()->rentals()->paginate(10); // แบ่งหน้าทีละ 10 รายการ
        return view('user.orders', compact('rentals'));
    }
}
