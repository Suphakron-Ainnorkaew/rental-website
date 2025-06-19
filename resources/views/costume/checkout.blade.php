@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">ยืนยันการเช่า</h1>

    <div class="bg-white shadow-lg rounded-xl p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">รายละเอียดการเช่า</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>ชุด:</strong> {{ $costume->name }}</p>
                    <p><strong>ราคาต่อวัน:</strong> ฿{{ number_format($costume->price) }}</p>
                    <p><strong>จำนวนชุด:</strong> {{ $quantity ?? 1 }} ชุด</p> <!-- เพิ่ม quantity -->
                </div>
                <div>
                    <p><strong>วันที่เริ่มเช่า:</strong> {{ $start_date }}</p>
                    <p><strong>วันที่สิ้นสุด:</strong> {{ $end_date }}</p>
                    <p><strong>จำนวนวัน:</strong> 
                        @php
                            $start = new DateTime($start_date);
                            $end = new DateTime($end_date);
                            $diff = $end->diff($start)->days + 1;
                            echo $diff . ' วัน';
                        @endphp
                    </p>
                    <p><strong>ราคารวม:</strong> ฿{{ number_format($diff * $costume->price * ($quantity ?? 1)) }}</p> <!-- ปรับคำนวณ -->
                </div>
            </div>
        </div>

        <form action="{{ route('costume.payment') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="costume_id" value="{{ $costume->id }}">
            <input type="hidden" name="start_date" value="{{ $start_date }}">
            <input type="hidden" name="end_date" value="{{ $end_date }}">
            <input type="hidden" name="quantity" value="{{ $quantity ?? 1 }}">
            <input type="hidden" name="rental_id" value="{{ $rental_id }}"> <!-- เพิ่ม rental_id -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="address" class="block text-gray-700 font-medium mb-1">ที่อยู่จัดส่ง</label>
                    <textarea name="address" id="address" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" required>{{ auth()->user()->address ?? '' }}</textarea>
                    @error('address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="phone_number" class="block text-gray-700 font-medium mb-1">เบอร์โทรศัพท์</label>
                    <input type="text" name="phone_number" id="phone_number" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" 
                        value="{{ auth()->user()->phone_number ?? '' }}" required>
                    @error('phone_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="national_id" class="block text-gray-700 font-medium mb-1">เลขบัตรประชาชน</label>
                    <input type="text" name="national_id" id="national_id" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" 
                        value="{{ auth()->user()->national_id ?? '' }}" required>
                    @error('national_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="w-full bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 transition duration-200 font-medium text-lg">
                ยืนยันสถานที่จัดส่ง
            </button>
        </form>
    </div>
</div>
@endsection