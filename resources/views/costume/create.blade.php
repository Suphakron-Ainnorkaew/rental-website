@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">เพิ่มชุดใหม่</h1>

    <form action="{{ route('admin.costume.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-xl p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-gray-700 font-medium mb-1">ชื่อชุด</label>
                <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded-lg p-3" required>
            </div>
            <div>
                <label for="category_id" class="block text-gray-700 font-medium mb-1">หมวดหมู่</label>
                <select name="category_id" id="category_id" class="w-full border border-gray-300 rounded-lg p-3" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="price" class="block text-gray-700 font-medium mb-1">ราคา (บาท)</label>
                <input type="number" name="price" id="price" step="0.01" class="w-full border border-gray-300 rounded-lg p-3" required>
            </div>
            <div>
                <label for="stock" class="block text-gray-700 font-medium mb-1">จำนวนในสต็อก</label>
                <input type="number" name="stock" id="stock" class="w-full border border-gray-300 rounded-lg p-3" required>
            </div>
            <div class="col-span-2">
                <label for="image" class="block text-gray-700 font-medium mb-1">รูปภาพ</label>
                <input type="file" name="image" id="image" class="w-full border border-gray-300 rounded-lg p-3" accept="image/*" required>
            </div>
            <div class="col-span-2">
                <label for="description" class="block text-gray-700 font-medium mb-1">คำอธิบาย</label>
                <textarea name="description" id="description" class="w-full border border-gray-300 rounded-lg p-3"></textarea>
            </div>
        </div>
        <button type="submit" class="mt-6 w-full bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 transition duration-200 font-medium text-lg">
            เพิ่มชุด
        </button>
    </form>
</div>
@endsection