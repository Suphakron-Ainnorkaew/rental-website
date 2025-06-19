@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">ข้อมูลส่วนตัว</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 shadow-md rounded-lg">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Avatar -->
                    <div class="flex flex-col items-center">
                        <div class="w-32 h-32 rounded-full bg-pink-100 border-2 border-pink-200 overflow-hidden flex items-center justify-center mb-4">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-pink-600 font-bold text-4xl">{{ substr($user->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <input type="file" name="avatar" class="mt-2 text-sm">
                        @error('avatar')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Form Fields -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700">ชื่อ</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded-md p-2" required>
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">อีเมล</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded-md p-2" required>
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">ที่อยู่</label>
                            <textarea name="address" class="w-full border rounded-md p-2" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full border rounded-md p-2">
                            @error('phone_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">เลขบัตรประชาชน</label>
                            <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}" class="w-full border rounded-md p-2">
                            @error('national_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700">บันทึก</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection