@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">จัดการคำสั่งเช่า</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- ตารางรายการรอการยืนยัน -->
    <div class="bg-white shadow-lg rounded-xl p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">รายการเช่ารอการยืนยัน</h2>

        @if(!isset($rentals) || $rentals->isEmpty())
            <p class="text-gray-600">ไม่มีรายการเช่ารอการยืนยัน</p>
        @else
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">เลขออเดอร์</th>
                        <th class="py-2">ผู้ใช้</th>
                        <th class="py-2">ชุด</th>
                        <th class="py-2">วันที่เริ่ม</th>
                        <th class="py-2">วันที่สิ้นสุด</th>
                        <th class="py-2">ราคารวม</th>
                        <th class="py-2">หลักฐาน</th>
                        <th class="py-2">การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rentals as $rental)
                        <tr class="border-b">
                            <td class="py-2">#{{ $rental->id }}</td>
                            <td class="py-2">{{ $rental->user->name }}</td>
                            <td class="py-2">{{ $rental->costume->name }}</td>
                            <td class="py-2">{{ $rental->start_date }}</td>
                            <td class="py-2">{{ $rental->end_date }}</td>
                            <td class="py-2">
                                @php
                                    $diff = (new DateTime($rental->end_date))->diff(new DateTime($rental->start_date))->days + 1;
                                    $total = $diff * $rental->costume->price * $rental->quantity;
                                    echo '฿' . number_format($total);
                                @endphp
                            </td>
                            <td class="py-2">
                                @if ($rental->payment_proof)
                                    <a href="{{ asset('storage/' . $rental->payment_proof) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $rental->payment_proof) }}" alt="Payment Proof" class="w-16 h-16 object-cover rounded">
                                    </a>
                                @else
                                    ไม่มี
                                @endif
                            </td>
                            <td class="py-2 flex space-x-2">
                                <a href="{{ route('admin.chat', $rental->id) }}" class="bg-blue-500 text-white py-1 px-3 rounded-lg hover:bg-blue-600">แชท</a>
                                <form action="{{ route('admin.rentals.confirm', $rental->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white py-1 px-3 rounded-lg hover:bg-green-600">ยืนยัน</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- ตารางรายการที่ถูกยกเลิก -->
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">รายการที่ถูกยกเลิก</h2>
        <p class="text-gray-600">ยังไม่มีการยกเลิกคำสั่งเช่า</p>
    </div>
</div>

@if(isset($rentals) && $rentals->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            alert('มีรายการเช่ารอการยืนยัน {{ $rentals->count() }} รายการ');
        });
    </script>
@endif
@endsection