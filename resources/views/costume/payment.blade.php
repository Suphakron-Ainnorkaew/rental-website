@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">ชำระเงิน</h1>

    <div class="bg-white shadow-lg rounded-xl p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">รายละเอียดการเช่า</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>ชุด:</strong> {{ $costume->name }}</p>
                    <p><strong>ราคาต่อวัน:</strong> ฿{{ number_format($costume->price) }}</p>
                    <p><strong>จำนวนชุด:</strong> {{ $quantity ?? 1 }} ชุด</p>
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
                    <p><strong>ราคารวม:</strong> ฿{{ number_format($diff * $costume->price * ($quantity ?? 1)) }}</p>
                </div>
            </div>
        </div>

        <div class="mb-6 text-center">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">กรุณาชำระเงินผ่าน QR Code</h2>
            <img src="{{ asset('images/qr-code.png.jpg') }}" alt="QR Code" class="mx-auto w-48 h-48 mb-4">
            <p class="text-gray-600">สแกน QR Code เพื่อโอนเงินจำนวน ฿{{ number_format($diff * $costume->price * ($quantity ?? 1)) }}</p>
            <p class="text-gray-500 text-sm mt-2">ธนาคาร: [ธนาคารกรุงเทพ] | ชื่อบัญชี: [ชนกนันท์ ไพรทองคีรี]</p>
        </div>

        <form action="{{ route('costume.payment') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="rental_id" value="{{ $rental->id }}">
            <input type="hidden" name="costume_id" value="{{ $costume->id }}">
            <input type="hidden" name="start_date" value="{{ $start_date }}">
            <input type="hidden" name="end_date" value="{{ $end_date }}">
            <input type="hidden" name="quantity" value="{{ $quantity ?? 1 }}">

            <div>
                <label class="block text-gray-700">แนบหลักฐานการชำระเงิน</label>
                <input type="file" name="payment_proof" class="w-full border rounded-md p-2" accept="image/*" required>
                @error('payment_proof')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            @if ($errors->any())
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="w-full bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 transition duration-200 font-medium text-lg">
                ยืนยันการชำระเงิน
            </button>
        </form>
    </div>
</div>
@endsection