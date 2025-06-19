@extends('layouts.app')

@section('title', 'โปรโมชั่น - Dable P')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">โปรโมชั่น</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-gray-600">
                พบกับโปรโมชั่นพิเศษสำหรับการเช่าชุด! 
                ลดสูงสุด 20% สำหรับการเช่าครั้งแรกเมื่อสมัครสมาชิกวันนี้ 
                <a href="{{ route('register') }}" class="text-pink-600 hover:underline">สมัครเลย</a>
            </p>
        </div>
    </div>
@endsection