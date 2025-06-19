@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">ชุดแนะนำ</h1>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach ($featured_costumes as $costume)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <img 
                    src="{{ asset('storage/' . $costume->image) }}" 
                    alt="{{ $costume->name }}" 
                    class="w-full h-72 object-cover"
                >
                <div class="p-4">
                    <h3 class="font-bold text-lg">{{ $costume->name }}</h3>
                    <p class="text-pink-600">฿{{ number_format($costume->price) }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{ $featured_costumes->links() }}
</div>
@endsection