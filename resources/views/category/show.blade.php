@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ $category->name }}</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($costumes as $costume)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <img src="{{ $costume->image ? Storage::url($costume->image) : asset('images/placeholder.jpg') }}" alt="{{ $costume->name }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ $costume->name }}</h3>
                        <p class="text-gray-600">ราคา: {{ number_format($costume->price) }} บาท</p>
                        <p class="text-gray-600">คงเหลือ: {{ $costume->stock }} ชุด</p>
                        @if ($costume->rentals->where('status', 'active')->count() > 0)
                            <p class="text-red-500">ถูกเช่าแล้ว</p>
                        @else
                            <a href="{{ route('costume.rent', $costume->id) }}" class="mt-2 block text-center bg-pink-600 text-white py-2 rounded-md hover:bg-pink-700">เช่าชุดนี้</a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-600">ไม่มีชุดในหมวดหมู่นี้</p>
            @endforelse
        </div>
    </div>
@endsection