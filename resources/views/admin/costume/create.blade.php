@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">เพิ่มชุดใหม่</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 shadow-md rounded-lg">
        <form action="{{ route('admin.costume.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">ชื่อชุด</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-md p-2" required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700">หมวดหมู่</label>
                    <select name="category_id" class="w-full border rounded-md p-2" required>
                        <option value="">เลือกหมวดหมู่</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700">ราคา (บาท)</label>
                    <input type="number" name="price" value="{{ old('price') }}" step="1" class="w-full border rounded-md p-2" required>
                    @error('price')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700">จำนวน</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" class="w-full border rounded-md p-2" required>
                    @error('stock')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">รูปภาพ</label>
                    <input type="file" name="image" class="w-full border rounded-md p-2" required>
                    @error('image')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">รายละเอียด</label>
                    <textarea name="description" class="w-full border rounded-md p-2" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button type="submit" class="mt-4 bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700">บันทึก</button>
        </form>
    </div>
</div>
@endsection