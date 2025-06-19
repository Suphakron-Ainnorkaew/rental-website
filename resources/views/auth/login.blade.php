@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-700">เข้าสู่ระบบ</h2>

        <form method="POST" action="{{ route('login') }}" class="mt-4">
            @csrf

            <!-- อีเมล -->
            <div>
                <label for="email" class="block text-gray-700">อีเมล</label>
                <input type="email" name="email" id="email" class="w-full p-2 border rounded mt-1" required>
            </div>

            <!-- รหัสผ่าน -->
            <div class="mt-4">
                <label for="password" class="block text-gray-700">รหัสผ่าน</label>
                <input type="password" name="password" id="password" class="w-full p-2 border rounded mt-1" required>
            </div>

            <!-- ปุ่มเข้าสู่ระบบ -->
            <div class="mt-6">
                <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700">
                    เข้าสู่ระบบ
                </button>
            </div>

            <!-- ลิงก์ไปหน้าสมัครสมาชิก -->
            <p class="mt-4 text-center text-gray-600">
                ยังไม่มีบัญชี? <a href="{{ route('register') }}" class="text-pink-600 hover:underline">สมัครสมาชิก</a>
            </p>
        </form>
    </div>
</div>
@endsection
