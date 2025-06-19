@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-700">สมัครสมาชิก</h2>

        <form method="POST" action="{{ route('register') }}" class="mt-4">
            @csrf

            <!-- ชื่อ -->
            <div>
                <label for="name" class="block text-gray-700">ชื่อ</label>
                <input type="text" name="name" id="name" class="w-full p-2 border rounded mt-1" required>
            </div>

            <!-- อีเมล -->
            <div class="mt-4">
                <label for="email" class="block text-gray-700">อีเมล</label>
                <input type="email" name="email" id="email" class="w-full p-2 border rounded mt-1" required>
            </div>

            <!-- รหัสผ่าน -->
            <div class="mt-4">
                <label for="password" class="block text-gray-700">รหัสผ่าน</label>
                <input type="password" name="password" id="password" class="w-full p-2 border rounded mt-1" required>
            </div>

            <!-- ยืนยันรหัสผ่าน -->
            <div class="mt-4">
                <label for="password_confirmation" class="block text-gray-700">ยืนยันรหัสผ่าน</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-2 border rounded mt-1" required>
            </div>

            <!-- ปุ่มสมัครสมาชิก -->
            <div class="mt-6">
                <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700">
                    สมัครสมาชิก
                </button>
            </div>

            <!-- ลิงก์ไปหน้าเข้าสู่ระบบ -->
            <p class="mt-4 text-center text-gray-600">
                มีบัญชีแล้ว? <a href="{{ route('login') }}" class="text-pink-600 hover:underline">เข้าสู่ระบบ</a>
            </p>
        </form>
    </div>
</div>
@endsection
