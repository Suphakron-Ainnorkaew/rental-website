@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 to-white">
    <!-- Hero Section with Improved Layout -->
    <div class="container mx-auto px-4 py-16 lg:py-24">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                เช่าชุดสวย <span class="text-pink-600">ง่ายนิดเดียว</span>
            </h1>
            
            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                คอลเลคชั่นพิเศษที่คัดสรรมาเพื่อคุณโดยเฉพาะ ทุกชุด ทุกสไตล์ ใส่แล้วปังการันตี
            </p>

            <!-- Enhanced Search Bar -->
            <div class="relative max-w-xl mx-auto">
                <form action="{{ route('search') }}" method="GET" class="flex shadow-2xl rounded-full overflow-hidden">
                    <input 
                        type="text" 
                        name="query" 
                        placeholder="ค้นหาชุดในฝัน..." 
                        class="w-full px-6 py-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-500"
                    >
                    <button 
                        type="submit" 
                        class="bg-pink-600 text-white px-6 hover:bg-pink-700 transition duration-300 flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="container mx-auto px-4 space-y-16">
        <!-- Category Filter with Horizontal Scroll -->
        <section>
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">หมวดหมู่</h2>
            <div class="flex space-x-4 overflow-x-auto pb-4">
                @foreach ($categories as $category)
                    <a href="{{ route('category.show', $category->id) }}" 
                       class="flex-shrink-0 px-6 py-3 bg-white text-gray-700 rounded-full shadow-md hover:bg-pink-50 hover:text-pink-600 transition duration-300">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Featured Costumes -->
        <section>
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 relative">
                    ชุดแนะนำ
                    <span class="absolute -bottom-2 left-0 w-1/2 h-1 bg-pink-500 rounded"></span>
                </h2>
                <a href="{{ route('featured') }}" class="text-pink-600 hover:underline">ดูทั้งหมด</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($featured_costumes as $costume)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition hover:-translate-y-2 hover:shadow-2xl">
                        <div class="relative aspect-[3/4]">
                            @if($costume->primaryImage)
                                <img 
                                    src="{{ Storage::url($costume->primaryImage->image_path) }}" 
                                    alt="{{ $costume->name }}" 
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <img 
                                    src="{{ asset('images/placeholder.jpg') }}" 
                                    alt="{{ $costume->name }}" 
                                    class="w-full h-full object-cover"
                                >
                            @endif
                            <div class="absolute top-4 right-4 bg-pink-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                แนะนำ
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-800 mb-2 truncate">
                                {{ $costume->name }}
                            </h3>
                            <div class="flex justify-between items-center">
                                <span class="text-pink-600 font-bold text-lg">
                                    ฿{{ number_format($costume->price) }}
                                </span>
                                <div class="flex items-center text-amber-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="ml-1">4.9</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- All Costumes with Sorting -->
        <section>
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 relative">
                    ชุดทั้งหมด
                    <span class="absolute -bottom-2 left-0 w-1/2 h-1 bg-pink-500 rounded"></span>
                </h2>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">เรียงตาม:</span>
                    <select class="form-select rounded-full border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200">
                        <option>ราคา: ต่ำ - สูง</option>
                        <option>ราคา: สูง - ต่ำ</option>
                        <option>ความนิยม</option>
                        <option>มาใหม่</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($costumes as $costume)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden relative">
                        <div class="relative aspect-[3/4]">
                            @if($costume->primaryImage)
                                <img 
                                    src="{{ Storage::url($costume->primaryImage->image_path) }}" 
                                    alt="{{ $costume->name }}" 
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <img 
                                    src="{{ asset('images/placeholder.jpg') }}" 
                                    alt="{{ $costume->name }}" 
                                    class="w-full h-full object-cover"
                                >
                            @endif
                            @if ($costume->stock < 3 && $costume->stock > 0)
                                <div class="absolute top-4 left-4 bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    เหลือน้อย
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-lg text-gray-800 truncate pr-2">
                                    {{ $costume->name }}
                                </h3>
                                <span class="bg-pink-100 text-pink-600 px-2 py-1 rounded text-xs">
                                    {{ $costume->category->name }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-pink-600 font-bold text-lg">
                                    ฿{{ number_format($costume->price) }}
                                </span>
                                <span class="text-gray-500 text-sm">
                                    @if ($costume->stock > 0)
                                        คงเหลือ: <span class="font-medium">{{ $costume->stock }}</span>
                                    @else
                                        <span class="text-red-500 font-medium">หมดสต๊อก</span>
                                    @endif
                                </span>
                            </div>
                            <div class="mt-4">
                                @php
                                    $activeRentalsQuantity = $costume->rentals->where('status', 'active')->sum('quantity');
                                    $availableStock = $costume->stock - $activeRentalsQuantity;
                                @endphp
                                @if ($availableStock <= 0)
                                    <button disabled class="w-full bg-gray-300 text-gray-600 py-3 rounded-lg cursor-not-allowed">
                                        ถูกเช่าแล้ว / หมดสต๊อก
                                    </button>
                                @elseif ($costume->stock > 0)
                                    <a href="{{ route('costume.rent', $costume->id) }}" 
                                    class="w-full block text-center bg-gradient-to-r from-pink-500 to-pink-600 text-white py-3 rounded-lg hover:from-pink-600 hover:to-pink-700 transition duration-300">
                                        เช่าเลย (คงเหลือ: {{ $availableStock }})
                                    </a>
                                @else
                                    <button disabled class="w-full bg-gray-300 text-gray-600 py-3 rounded-lg cursor-not-allowed">
                                        หมดสต๊อก
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $costumes->links() }}
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="bg-pink-50 rounded-2xl p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-10 relative inline-block">
                เสียงจากลูกค้า
                <span class="absolute -bottom-2 left-0 w-full h-1 bg-pink-500 rounded"></span>
            </h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                @foreach ([
                    ['name' => 'คุณนกน้อย', 'color' => 'pink', 'quote' => '"ชุดสวยมากๆค่ะ ใส่แล้วได้รับคำชมเยอะมาก บริการก็ดีสุดๆ จะกลับมาเช่าอีกแน่นอนค่ะ"'],
                    ['name' => 'คุณต้นไม้', 'color' => 'blue', 'quote' => '"ราคาไม่แพงเลยครับเมื่อเทียบกับคุณภาพของชุด จัดส่งรวดเร็ว และมีหลากหลายไซส์ให้เลือก แนะนำครับ"'],
                    ['name' => 'คุณฟ้าใส', 'color' => 'purple', 'quote' => '"บริการดีมาก เว็บไซต์ใช้งานง่าย ชุดสวยครบทุกสไตล์ แนะนำอย่างยิ่งค่ะ"']
                ] as $testimonial)
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold mr-4 bg-{{ $testimonial['color'] }}-500">
                                {{ substr($testimonial['name'], 3, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold">{{ $testimonial['name'] }}</h4>
                                <div class="flex text-amber-500">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600">{{ $testimonial['quote'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection