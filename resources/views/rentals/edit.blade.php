@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">แก้ไขการเช่า</h1>

        <div class="bg-white p-6 shadow-md rounded-lg">
            <form action="{{ route('rentals.update', $rental->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ชื่อชุด (Disabled) -->
                <div class="mb-4">
                    <label class="block text-gray-700">ชื่อชุดที่เช่า</label>
                    <input type="text" class="w-full p-2 border rounded bg-gray-100" value="{{ $rental->costume->name }}" disabled>
                </div>

                <!-- วันเริ่มเช่า -->
                <div class="mb-4">
                    <label class="block text-gray-700">วันที่เริ่มเช่า</label>
                    <input type="date" name="start_date" class="w-full p-2 border rounded"
                           value="{{ $rental->start_date ? (is_string($rental->start_date) ? $rental->start_date : $rental->start_date->format('Y-m-d')) : '' }}" required>
                    @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- วันสิ้นสุดการเช่า -->
                <div class="mb-4">
                    <label class="block text-gray-700">วันที่สิ้นสุดการเช่า</label>
                    <input type="date" name="end_date" class="w-full p-2 border rounded"
                           value="{{ $rental->end_date ? (is_string($rental->end_date) ? $rental->end_date : $rental->end_date->format('Y-m-d')) : '' }}" required>
                    @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- ปุ่มบันทึกและยกเลิก -->
                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">บันทึก</button>
                    <a href="{{ route('rentals.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
@endsection