@extends('layouts.app')

@section('title', 'การจัดส่ง - Dable P')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">การจัดส่ง</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-gray-600">
                เราจัดส่งชุดถึงคุณภายใน 2-3 วันทำการผ่านพันธมิตรขนส่งชั้นนำ 
                <a href="{{ route('track-shipping') }}" class="text-pink-600 hover:underline">ติดตามการจัดส่ง</a>
            </p>
        </div>
    </div>
@endsection