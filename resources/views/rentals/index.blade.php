@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">ประวัติการเช่า</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 shadow-md rounded-lg">
            @forelse ($rentals as $rental)
            <div class="border-b py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ $rental->costume->image ? asset('storage/' . $rental->costume->image) : asset('images/placeholder.jpg') }}" 
                        alt="{{ $rental->costume->name }}" class="w-20 h-20 object-cover rounded mr-4">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $rental->costume->name }}</h3>
                        <p class="text-gray-600">ราคา: {{ number_format($rental->costume->price) }} บาท</p>
                        <p class="text-gray-600">วันที่เช่า: {{ $rental->created_at->format('d/m/Y') }}</p>
                        <p class="text-sm">
                            สถานะ: 
                            @if ($rental->status === 'pending')
                                <span class="text-yellow-500">รอดำเนินการ</span>
                            @elseif ($rental->status === 'active')
                                <span class="text-green-500">กำลังเช่า</span>
                            @else
                                <span class="text-gray-500">สิ้นสุด</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex space-x-2">
                    @if ($rental->status === 'pending')
                        <button type="button" class="px-4 py-2 bg-red-500 text-white rounded" 
                                onclick="document.getElementById('cancel-modal-{{ $rental->id }}').classList.remove('hidden')">
                            ยกเลิก
                        </button>

                        <!-- Modal สำหรับกรอกเหตุผล -->
                        <div id="cancel-modal-{{ $rental->id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                                <h2 class="text-xl font-bold mb-4">ยกเลิกการเช่า</h2>
                                <form action="{{ route('rentals.destroy', $rental->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="mb-4">
                                        <label class="block text-gray-700">เหตุผลการยกเลิก</label>
                                        <textarea name="cancel_reason" class="w-full p-2 border rounded" rows="3" required></textarea>
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">ยืนยันการยกเลิก</button>
                                        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" 
                                                onclick="document.getElementById('cancel-modal-{{ $rental->id }}').classList.add('hidden')">
                                            ปิด
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @empty
                <p class="text-gray-600">คุณยังไม่มีประวัติการเช่า</p>
            @endforelse
        </div>
    </div>
@endsection