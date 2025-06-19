@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">แดชบอร์ดแอดมิน</h1>

    <div class="mb-6">
        <a href="{{ route('admin.rentals') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-pink-50 hover:text-pink-600">
            <i class="fas fa-user-circle w-5 mr-2"></i> ยืนยันคำสั่งซื้อ
        </a>
        <a href="{{ route('admin.chats') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-pink-50 hover:text-pink-600">
            <i class="fas fa-comments w-5 mr-2"></i> แชทกับผู้ใช้ (เลือกจากคำสั่งซื้อ)
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ฟอร์มเพิ่มชุดใหม่ -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6">เพิ่มชุดใหม่</h1>
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
                <div class="col-span-2">
                    <label class="block text-gray-700">สีและจำนวน</label>
                    <div id="color-fields">
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="colors[0][color]" class="w-1/2 border rounded-md p-2" placeholder="สี" required>
                            <input type="number" name="colors[0][stock]" class="w-1/2 border rounded-md p-2" placeholder="จำนวน" required>
                        </div>
                    </div>
                    <button type="button" id="add-color" class="text-blue-500 hover:underline">+ เพิ่มสี</button>
                    @if ($errors->has('colors.*.color') || $errors->has('colors.*.stock'))
                        <span class="text-red-500 text-sm">กรุณากรอกสีและจำนวนให้ครบถ้วน</span>
                    @endif
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">ไซส์และจำนวน</label>
                    <div id="size-fields">
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="sizes[0][size]" class="w-1/2 border rounded-md p-2" placeholder="ไซส์ (เช่น S, M, L)" required>
                            <input type="number" name="sizes[0][stock]" class="w-1/2 border rounded-md p-2" placeholder="จำนวน" required>
                        </div>
                    </div>
                    <button type="button" id="add-size" class="text-blue-500 hover:underline">+ เพิ่มไซส์</button>
                    @if ($errors->has('sizes.*.size') || $errors->has('sizes.*.stock'))
                        <span class="text-red-500 text-sm">กรุณากรอกไซส์และจำนวนให้ครบถ้วน</span>
                    @endif
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700">รูปภาพ (สามารถอัปโหลดหลายรูป)</label>
                    <input type="file" name="images[]" id="image-upload" class="w-full border rounded-md p-2" multiple required>
                    <p class="text-sm text-gray-500 mt-1">อัปโหลดได้หลายรูป โดยรูปแรกจะถูกตั้งเป็นรูปหลัก</p>
                    <div id="image-preview" class="mt-2 grid grid-cols-3 gap-2"></div>
                    @error('images')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    @error('images.*')
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

    <!-- รายการชุด -->
    <div class="mt-8 bg-white p-6 shadow-md rounded-lg">
        <h2 class="text-xl font-semibold mb-4">รายการชุด</h2>
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-2">รูปภาพ</th>
                    <th class="py-2">ชื่อชุด</th>
                    <th class="py-2">ราคา</th>
                    <th class="py-2">คงเหลือ</th>
                    <th class="py-2">สถานะ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($costumes as $costume)
                    <tr class="border-b">
                        <td class="py-2">
                            @if($costume->primaryImage)
                                <img src="{{ Storage::url($costume->primaryImage->image_path) }}" alt="{{ $costume->name }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $costume->name }}" class="w-16 h-16 object-cover rounded">
                            @endif
                            <span class="text-xs text-gray-500 block mt-1">{{ $costume->images->count() }} รูป</span>
                        </td>
                        <td class="py-2">{{ $costume->name }}</td>
                        <td class="py-2">{{ number_format($costume->price) }} บาท</td>
                        <td class="py-2">{{ $costume->stock }}</td>
                        <td class="py-2">
                            @if ($costume->rentals->where('status', 'active')->count() > 0)
                                <span class="text-red-500">ถูกเช่า</span>
                            @else
                                <span class="text-green-500">ว่าง</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-2 text-center text-gray-600">ไม่มีชุดในรายการ</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- สถานะการเช่า -->
    <div class="mt-8 bg-white p-6 shadow-md rounded-lg">
        <h2 class="text-xl font-semibold mb-4">สถานะการเช่า</h2>
        @if(!isset($rentals) || $rentals->isEmpty())
            <p class="text-gray-600">ยังไม่มีรายการเช่า</p>
        @else
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">ผู้ใช้ (ID)</th>
                        <th class="py-2">ชุด</th>
                        <th class="py-2">จำนวนวัน</th>
                        <th class="py-2">ราคารวม</th>
                        <th class="py-2">สถานะ</th>
                        <th class="py-2">เบอร์โทร</th>
                        <th class="py-2">ที่อยู่</th>
                        <th class="py-2">เลขบัตรประชาชน</th>
                        <th class="py-2">การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rentals as $rental)
                        <tr class="border-b">
                            <td class="py-2">{{ $rental->user->name }} (#{{ $rental->user->id }})</td>
                            <td class="py-2">{{ $rental->costume->name }}</td>
                            <td class="py-2">
                                @php
                                    $days = (new DateTime($rental->end_date))->diff(new DateTime($rental->start_date))->days + 1;
                                    echo $days;
                                @endphp
                            </td>
                            <td class="py-2">
                                @php
                                    $total = $days * $rental->costume->price * $rental->quantity;
                                    echo '฿' . number_format($total);
                                @endphp
                            </td>
                            <td class="py-2">
                                @switch($rental->status)
                                    @case('pending')
                                        <span class="text-yellow-500">รอการยืนยัน</span>
                                        @break
                                    @case('active')
                                        <span class="text-blue-500">ใช้งาน</span>
                                        @break
                                    @case('completed')
                                        <span class="text-green-500">คืนแล้ว</span>
                                        @break
                                    @case('cancelled')
                                        <span class="text-red-500">ยกเลิก</span>
                                        @break
                                    @default
                                        <span class="text-gray-500">ไม่ระบุ</span>
                                @endswitch
                            </td>
                            <td class="py-2">{{ $rental->user->phone_number ?? 'ไม่ระบุ' }}</td>
                            <td class="py-2">{{ $rental->user->address ?? 'ไม่ระบุ' }}</td>
                            <td class="py-2">{{ $rental->user->national_id ?? 'ไม่ระบุ' }}</td>
                            <td class="py-2">
                                @if($rental->status == 'active')
                                    <form action="{{ route('admin.rentals.complete', $rental->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-purple-500 text-white py-1 px-3 rounded-lg hover:bg-purple-600">คืนสินค้าสำเร็จ</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@push('scripts')
<script>
    let colorIndex = 1;
    document.getElementById('add-color').addEventListener('click', function() {
        const container = document.getElementById('color-fields');
        const newField = document.createElement('div');
        newField.className = 'flex gap-2 mb-2';
        newField.innerHTML = `
            <input type="text" name="colors[${colorIndex}][color]" class="w-1/2 border rounded-md p-2" placeholder="สี" required>
            <input type="number" name="colors[${colorIndex}][stock]" class="w-1/2 border rounded-md p-2" placeholder="จำนวน" required>
        `;
        container.appendChild(newField);
        colorIndex++;
    });

    let sizeIndex = 1;
    document.getElementById('add-size').addEventListener('click', function() {
        const container = document.getElementById('size-fields');
        const newField = document.createElement('div');
        newField.className = 'flex gap-2 mb-2';
        newField.innerHTML = `
            <input type="text" name="sizes[${sizeIndex}][size]" class="w-1/2 border rounded-md p-2" placeholder="ไซส์ (เช่น S, M, L)" required>
            <input type="number" name="sizes[${sizeIndex}][stock]" class="w-1/2 border rounded-md p-2" placeholder="จำนวน" required>
        `;
        container.appendChild(newField);
        sizeIndex++;
    });

    // ระบบแสดงตัวอย่างรูปภาพก่อนอัปโหลด
    document.getElementById('image-upload').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        
        if (this.files.length > 0) {
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-32 object-cover rounded border';
                        img.alt = 'Preview ' + (i + 1);
                        
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <span class="absolute top-1 left-1 bg-white bg-opacity-70 px-1 rounded text-xs">${i + 1}</span>
                        `;
                        div.prepend(img);
                        
                        preview.appendChild(div);
                    }
                    
                    reader.readAsDataURL(file);
                }
            }
        } else {
            preview.innerHTML = '<p class="text-gray-500 text-sm">ไม่มีรูปภาพที่เลือก</p>';
        }
    });
</script>
@endpush
@endsection