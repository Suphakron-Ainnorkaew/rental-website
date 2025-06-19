@extends('layouts.app')

@section('title', 'คำถามที่พบบ่อย - Dable P')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">คำถามที่พบบ่อย</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-gray-600">
                <strong>Q: ฉันจะเช่าชุดได้อย่างไร?</strong><br>
                A: ดู <a href="{{ route('how-to-rent') }}" class="text-pink-600 hover:underline">วิธีการเช่า</a>
            </p>
        </div>
    </div>
@endsection