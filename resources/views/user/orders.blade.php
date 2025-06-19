@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">ประวัติการเช่า</h1>

    <div class="bg-white p-6 shadow-md rounded-lg">
        @forelse ($rentals as $rental)
        <div class="border-b py-4 flex flex-col md:flex-row items-start md:items-center justify-between">
            <div class="flex items-start w-full md:w-auto">
                @if($rental->costume->primaryImage)
                    <img src="{{ Storage::url($rental->costume->primaryImage->image_path) }}" 
                         alt="{{ $rental->costume->name }}" 
                         class="w-20 h-20 object-cover rounded mr-4">
                @else
                    <img src="{{ asset('images/placeholder.jpg') }}" 
                         alt="{{ $rental->costume->name }}" 
                         class="w-20 h-20 object-cover rounded mr-4">
                @endif
                <div class="flex-1">
                    <h3 class="text-lg font-semibold">{{ $rental->costume->name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-2">
                        <div>
                            <p class="text-gray-600 text-sm">ราคาต่อวัน</p>
                            <p>{{ number_format($rental->costume->price) }} บาท</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">จำนวนวัน</p>
                            <p>
                                @php
                                    $days = (new DateTime($rental->end_date))->diff(new DateTime($rental->start_date))->days + 1;
                                    echo $days;
                                @endphp วัน
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">ราคารวม</p>
                            <p class="font-semibold text-pink-600">
                                @php
                                    $total = $days * $rental->costume->price * $rental->quantity;
                                    echo number_format($total) . ' บาท';
                                @endphp
                            </p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-gray-600 text-sm">วันที่เช่า: {{ \Carbon\Carbon::parse($rental->created_at)->format('d/m/Y') }}</p>
                        <p class="text-gray-600 text-sm">ระยะเวลาเช่า: 
                            {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}
                        </p>
                        <p class="text-sm mt-1">
                            สถานะ: 
                            @if ($rental->status === 'pending')
                                <span class="text-yellow-500">รอดำเนินการ</span>
                            @elseif ($rental->status === 'active')
                                <span class="text-green-500">กำลังเช่า</span>
                            @elseif ($rental->status === 'completed')
                                <span class="text-blue-500">เช่าเสร็จสิ้น</span>
                            @else
                                <span class="text-gray-500">ยกเลิก</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- ปุ่มจัดการ --}}
            <div class="flex space-x-2 mt-4 md:mt-0">
                @if ($rental->status === 'pending')
                    <form action="{{ route('rentals.destroy', $rental->id) }}" method="POST"
                        onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการเช่านี้?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                            ยกเลิก
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @empty
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-600 mt-4">คุณยังไม่มีประวัติการเช่า</p>
                <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
                    ดูชุดทั้งหมด
                </a>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($rentals->hasPages())
        <div class="mt-6">
            {{ $rentals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection