@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('home') }}" class="text-pink-600 hover:text-pink-800 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            <span>กลับไปหน้ารายการชุด</span>
        </a>
    </div>

    @php
        // คำนวณสต็อกที่แท้จริงโดยลบจำนวนชุดที่ถูกเช่าอยู่ (เฉพาะสถานะ pending และ active)
        $activeRentals = $costume->rentals->whereIn('status', ['pending', 'active'])->sum('quantity');
        $availableStock = max(0, $costume->stock - $activeRentals);
    @endphp

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                <div class="relative h-96">
                    <img src="{{ $costume->image ? Storage::url($costume->image) : asset('images/placeholder.jpg') }}" 
                         alt="{{ $costume->name }}" 
                         class="w-full h-full object-cover">
                    @if($availableStock < 5 && $availableStock > 0)
                    <div class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                        เหลือเพียง {{ $availableStock }} ชุด
                    </div>
                    @elseif($availableStock == 0)
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                        สินค้าหมด
                    </div>
                    @endif
                </div>
            </div>
            <div class="md:w-1/2 p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $costume->name }}</h1>
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= ($costume->rating ?? 4))
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784 .57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81 .588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3 .922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783 .57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81 .588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <span class="ml-2 text-gray-600 text-sm">({{ $costume->reviews_count ?? rand(10, 50) }} รีวิว)</span>
                </div>
                
                <div class="mb-6">
                    <div class="text-3xl font-bold text-pink-600 mb-1">฿{{ number_format($costume->price) }} <span class="text-sm font-normal text-gray-500">/ วัน</span></div>
                    <p class="text-gray-700 mb-4">{{ $costume->description ?? 'ชุดสวยงามที่เหมาะสำหรับทุกโอกาสพิเศษ สวมใส่สบาย เนื้อผ้าคุณภาพดี' }}</p>
                    
                    <div class="flex flex-wrap gap-4 mb-4">
                        <div class="bg-gray-100 rounded-lg px-4 py-2">
                            <span class="text-gray-600 text-sm">สถานะ</span>
                            <p class="font-medium text-gray-800">{{ $availableStock > 0 ? 'พร้อมเช่า' : 'ไม่พร้อมเช่า' }}</p>
                        </div>
                        <div class="bg-gray-100 rounded-lg px-4 py-2">
                            <span class="text-gray-600 text-sm">คงเหลือ</span>
                            <p class="font-medium text-gray-800">{{ $availableStock }} ชุด</p>
                        </div>
                        <div class="bg-gray-100 rounded-lg px-4 py-2">
                            <span class="text-gray-600 text-sm">ประเภท</span>
                            <p class="font-medium text-gray-800">{{ $costume->category->name ?? 'ชุดราตรี' }}</p>
                        </div>
                    </div>
                </div>

                @if($availableStock > 0)
                <form action="{{ route('costume.checkout') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="costume_id" value="{{ $costume->id }}">
                    
                    <!-- ตัวเลือกสี -->
                    <div>
                        <label for="color_id" class="block text-gray-700 font-medium mb-1">เลือกสี</label>
                        <select name="color_id" id="color_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" required>
                            <option value="">-- เลือกสี --</option>
                            @foreach($costume->colors as $color)
                                @php
                                    $activeRentalsForColor = $costume->rentals->where('color_id', $color->id)
                                        ->whereIn('status', ['pending', 'active'])
                                        ->sum('quantity');
                                    $availableColorStock = max(0, $color->stock - $activeRentalsForColor);
                                @endphp
                                @if($availableColorStock > 0)
                                    <option value="{{ $color->id }}" data-stock="{{ $availableColorStock }}">{{ $color->value }} (คงเหลือ {{ $availableColorStock }})</option>
                                @endif
                            @endforeach
                        </select>
                        @error('color_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ตัวเลือกไซส์ -->
                    <div>
                        <label for="size_id" class="block text-gray-700 font-medium mb-1">เลือกไซส์</label>
                        <select name="size_id" id="size_id" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" required>
                            <option value="">-- เลือกไซส์ --</option>
                            @foreach($costume->sizes as $size)
                                @php
                                    $activeRentalsForSize = $costume->rentals->where('size_id', $size->id)
                                        ->whereIn('status', ['pending', 'active'])
                                        ->sum('quantity');
                                    $availableSizeStock = max(0, $size->stock - $activeRentalsForSize);
                                @endphp
                                @if($availableSizeStock > 0)
                                    <option value="{{ $size->id }}" data-stock="{{ $availableSizeStock }}">{{ $size->value }} (คงเหลือ {{ $availableSizeStock }})</option>
                                @endif
                            @endforeach
                        </select>
                        @error('size_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-gray-700 font-medium mb-1">วันที่เริ่มเช่า</label>
                            <div class="relative">
                                <input type="date" name="start_date" id="start_date" 
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-gray-700 font-medium mb-1">วันที่สิ้นสุด</label>
                            <div class="relative">
                                <input type="date" name="end_date" id="end_date" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- จำนวนชุด -->
                    <div>
                        <label for="quantity" class="block text-gray-700 font-medium mb-1">จำนวนชุดที่ต้องการเช่า</label>
                        <select name="quantity" id="quantity" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-500 focus:border-pink-500" required>
                            <option value="1">1 ชุด</option>
                        </select>
                        @error('quantity')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">ราคาต่อวัน</span>
                            <span id="price-per-day" data-price="{{ $costume->price }}">฿{{ number_format($costume->price) }}</span>
                        </div>
                        <div class="flex justify-between mb-2" id="days-count">
                            <span class="text-gray-600">จำนวนวัน</span>
                            <span>0 วัน</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">จำนวนชุด</span>
                            <span id="quantity-display">1 ชุด</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>ราคารวม</span>
                            <span class="text-pink-600" id="total-price">฿0</span>
                        </div>
                    </div>
                    
                    @if ($errors->any())
                        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="w-full bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 transition duration-200 font-medium text-lg">
                        ไปหน้าชำระเงิน
                    </button>
                </form>
                @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <p class="text-red-600 font-medium">ขออภัย ชุดนี้ไม่พร้อมให้เช่าในขณะนี้</p>
                    <p class="text-gray-600 text-sm mt-1">กรุณาเลือกชุดอื่น หรือติดต่อเจ้าหน้าที่เพื่อสอบถามเพิ่มเติม</p>
                </div>
                <a href="{{ route('home') }}" class="block w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition duration-200 font-medium text-center text-lg">
                    ดูชุดอื่นๆ
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const colorSelect = document.getElementById('color_id');
    const sizeSelect = document.getElementById('size_id');
    const quantitySelect = document.getElementById('quantity');
    const pricePerDay = document.getElementById('price-per-day').dataset.price;
    const daysCount = document.getElementById('days-count').querySelector('span:last-child');
    const quantityDisplay = document.getElementById('quantity-display');
    const totalPrice = document.getElementById('total-price');

    function updateQuantityOptions() {
        const selectedColor = colorSelect.options[colorSelect.selectedIndex];
        const selectedSize = sizeSelect.options[sizeSelect.selectedIndex];
        const colorStock = selectedColor && selectedColor.value ? parseInt(selectedColor.dataset.stock) : Infinity;
        const sizeStock = selectedSize && selectedSize.value ? parseInt(selectedSize.dataset.stock) : Infinity;
        const maxStock = Math.min(colorStock, sizeStock, {{ $availableStock }});

        quantitySelect.innerHTML = '';
        const stockLimit = maxStock > 0 ? maxStock : 1;
        for (let i = 1; i <= stockLimit; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = `${i} ชุด`;
            quantitySelect.appendChild(option);
        }
        quantitySelect.value = '1'; // ตั้งค่าเริ่มต้น
        updateTotal();
    }

    function updateTotal() {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        const qty = parseInt(quantitySelect.value);

        if (start && end && !isNaN(qty)) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            const total = diffDays * pricePerDay * qty;

            daysCount.textContent = `${diffDays} วัน`;
            quantityDisplay.textContent = `${qty} ชุด`;
            totalPrice.textContent = `฿${total.toLocaleString()}`;
        }
    }

    colorSelect.addEventListener('change', updateQuantityOptions);
    sizeSelect.addEventListener('change', updateQuantityOptions);
    startDate.addEventListener('change', updateTotal);
    endDate.addEventListener('change', updateTotal);
    quantitySelect.addEventListener('change', updateTotal);

    updateQuantityOptions(); // เรียกตอนโหลดหน้า
});
</script>
@endpush
@endsection