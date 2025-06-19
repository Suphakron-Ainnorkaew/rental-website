<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'เช่าชุดออนไลน์ | Dable P - ให้คุณสวยได้ในทุกโอกาสพิเศษ' }}</title>
    <meta name="description" content="บริการเช่าชุดออนไลน์คุณภาพสูง ทั้งชุดราตรี ชุดไทย ชุดแต่งงาน และอื่นๆ อีกมากมาย ส่งตรงถึงบ้านคุณ">
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- ใช้ Vite แทน Tailwind CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
        }
        .dropdown-menu {
            transform: translateY(10px);
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        .group:hover .dropdown-menu {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        .animated-underline {
            position: relative;
        }
        .animated-underline::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #DB2777;
            transition: width 0.3s ease;
        }
        .animated-underline:hover::after,
        .animated-underline.active::after {
            width: 100%;
        }
        .shimmer-bg {
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0) 100%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .scroll-nav {
            transition: all 0.3s ease;
        }
        @media (max-width: 640px) {
            .mobile-menu-open {
                transform: translateX(0%);
            }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50">
    <!-- Announcement Bar -->
    <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm font-medium">
                    <i class="fas fa-gift mr-2"></i>
                    <span>ส่วนลด 20% สำหรับการเช่าครั้งแรก! ใช้โค้ด: <span class="font-bold">NEWCHIC</span></span>
                </div>
                <div class="flex items-center space-x-4 text-xs">
                    <a href="{{ route('help') }}" class="hover:underline"><i class="fas fa-question-circle mr-1"></i> ช่วยเหลือ</a>
                    <a href="{{ route('track-shipping') }}" class="hover:underline"><i class="fas fa-truck mr-1"></i> ติดตามการจัดส่ง</a>
                    <a href="{{ route('contact') }}" class="hover:underline"><i class="fas fa-phone-alt mr-1"></i> ติดต่อเรา</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav id="main-nav" class="bg-white shadow-md sticky top-0 z-50 scroll-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center mr-2">
                            <span class="text-white font-bold text-xl">P</span>
                        </div>
                        <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-purple-600">
                            Dable P
                        </span>
                    </a>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex flex-1 max-w-md mx-6">
                    <form action="{{ route('search') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="query" placeholder="ค้นหาชุดในฝัน..." 
                                   class="w-full py-2 pl-4 pr-10 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <button type="submit" class="absolute right-0 top-0 h-full px-3 flex items-center text-gray-400 hover:text-pink-500">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden sm:flex sm:items-center space-x-8">
                    <a href="{{ route('home') }}" class="animated-underline font-medium {{ request()->routeIs('home') ? 'text-pink-600 active' : 'text-gray-700 hover:text-pink-600' }}">
                        หน้าแรก
                    </a>
                    <div class="relative group">
                        <button class="animated-underline font-medium text-gray-700 hover:text-pink-600 flex items-center">
                            หมวดหมู่
                            <i class="fas fa-chevron-down ml-1 text-xs transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="dropdown-menu absolute right-0 w-56 mt-2 bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100 z-20">
                            <div class="py-2">
                                @forelse ($categories ?? [] as $category)
                                    <a href="{{ route('category.show', $category->id) }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition duration-150">
                                        <span class="w-2 h-2 rounded-full bg-pink-400 mr-2"></span>
                                        {{ $category->name }}
                                    </a>
                                @empty
                                    <p class="px-4 py-2 text-gray-500">ไม่มีหมวดหมู่</p>
                                @endforelse
                                <div class="border-t border-gray-100 mt-2 pt-2 px-4">
                                    <a href="{{ route('categories') }}" class="flex items-center py-2 text-pink-600 font-medium hover:text-pink-700">
                                        <i class="fas fa-th-large mr-2"></i>
                                        ดูทั้งหมด
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('promotions') }}" class="animated-underline font-medium {{ request()->routeIs('promotions') ? 'text-pink-600 active' : 'text-gray-700 hover:text-pink-600' }}">
                        โปรโมชั่น
                    </a>
                    <a href="{{ route('how-to-rent') }}" class="animated-underline font-medium {{ request()->routeIs('how-to-rent') ? 'text-pink-600 active' : 'text-gray-700 hover:text-pink-600' }}">
                        วิธีการเช่า
                    </a>

                    <!-- User Actions -->
                    <div class="flex items-center space-x-6 ml-4">
                        <!-- Wishlist icon -->
                        <a href="{{ route('wishlist') }}" class="text-gray-700 hover:text-pink-600 relative">
                            <i class="fas fa-heart text-xl"></i>
                        </a>
                        
                        <!-- Cart icon -->
                        <a href="{{ route('cart') }}" class="text-gray-700 hover:text-pink-600 relative">
                            <i class="fas fa-shopping-bag text-xl"></i>
                            <span class="absolute -top-2 -right-2 w-5 h-5 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center">
                                {{ session()->has('cart') ? count(session('cart')) : '0' }}
                            </span>
                        </a>

                        <!-- Chat with Admin (Desktop) -->
                        @auth
                            @if(Auth::user()->role !== 'admin')
                                <a href="{{ route('user.chat') }}" class="text-gray-700 hover:text-pink-600 relative" title="แชทกับแอดมิน">
                                    <i class="fas fa-comment-dots text-xl"></i>
                                </a>
                            @endif
                        @endauth
                        
                        <!-- User Account -->
                        @auth
                            <div class="relative group">
                                <button class="flex items-center focus:outline-none">
                                    <div class="w-9 h-9 rounded-full bg-pink-100 border-2 border-pink-200 overflow-hidden flex items-center justify-center">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-pink-600 font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <span class="ml-2 font-medium hidden lg:block">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down ml-1 text-xs transition-transform group-hover:rotate-180"></i>
                                </button>
                                <div class="dropdown-menu absolute right-0 w-56 mt-2 bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100 z-20">
                                    <div class="py-3 px-4 border-b border-gray-100">
                                        <p class="text-sm text-gray-500">สวัสดี,</p>
                                        <p class="font-medium">{{ Auth::user()->name }}</p>
                                    </div>
                                    <div class="py-2">
                                        <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-pink-50 hover:text-pink-600">
                                            <i class="fas fa-user-circle w-5 mr-2"></i>
                                            ข้อมูลส่วนตัว
                                        </a>
                                        <a href="{{ route('orders') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-pink-50 hover:text-pink-600">
                                            <i class="fas fa-shopping-basket w-5 mr-2"></i>
                                            ประวัติการเช่า
                                        </a>
                                        <a href="{{ route('wishlist') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-pink-50 hover:text-pink-600">
                                            <i class="fas fa-heart w-5 mr-2"></i>
                                            รายการโปรด
                                        </a>
                                        @if (Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-3 rounded-lg text-gray-700 hover:bg-pink-50 hover:text-pink-600">
                                                <i class="fas fa-user-shield mr-2 w-6 text-center"></i>
                                                แอดมิน
                                            </a>
                                            <a href="{{ route('admin.chats') }}" class="block px-3 py-3 rounded-lg text-gray-700 hover:bg-pink-50 hover:text-pink-600">
                                                <i class="fas fa-comment-dots mr-2 w-6 text-center"></i>
                                                แชททั้งหมด
                                            </a>
                                        @endif
                                        <div class="border-t border-gray-100 mt-2">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-pink-50 hover:text-pink-600">
                                                    <i class="fas fa-sign-out-alt w-5 mr-2"></i>
                                                    ออกจากระบบ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('login') }}" class="px-4 py-2 rounded-full font-medium text-pink-600 hover:text-pink-700 border border-pink-200 hover:border-pink-300 transition duration-150">
                                    เข้าสู่ระบบ
                                </a>
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-full font-medium text-white bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 shadow-sm hover:shadow transition duration-150">
                                    สมัครสมาชิก
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden space-x-4">
                    <a href="#" class="text-gray-700 p-2 rounded-md hover:bg-gray-100">
                        <i class="fas fa-search text-lg"></i>
                    </a>
                    <a href="{{ route('cart') }}" class="text-gray-700 p-2 rounded-md hover:bg-gray-100 relative">
                        <i class="fas fa-shopping-bag text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center">
                            {{ session()->has('cart') ? count(session('cart')) : '0' }}
                        </span>
                    </a>
                    <!-- Chat with Admin (Mobile) -->
                    @auth
                        @if(Auth::user()->role !== 'admin')
                            <a href="{{ route('user.chat') }}" class="text-gray-700 p-2 rounded-md hover:bg-gray-100" title="แชทกับแอดมิน">
                                <i class="fas fa-comment-dots text-lg"></i>
                            </a>
                        @endif
                    @endauth
                    <button id="mobile-menu-button" class="text-gray-700 p-2 rounded-md hover:bg-gray-100">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="fixed top-0 right-0 w-4/5 h-full bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out z-50">
            <div class="p-5 border-b border-gray-200 flex justify-between items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center mr-2">
                        <span class="text-white font-bold text-sm">P</span>
                    </div>
                    <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-purple-600">
                        Dable P
                    </span>
                </a>
                <button id="close-mobile-menu" class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <div class="py-3 px-2">
                @auth
                    <div class="flex items-center p-3 mb-3 bg-pink-50 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-pink-100 border-2 border-pink-200 overflow-hidden flex items-center justify-center">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-pink-600 font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                @endauth
                
                <div class="space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-3 rounded-lg {{ request()->routeIs('home') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-home mr-2 w-6 text-center"></i>
                        หน้าแรก
                    </a>
                    
                    <div id="categories-collapse-button" class="flex justify-between items-center px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-50 cursor-pointer">
                        <div>
                            <i class="fas fa-th-large mr-2 w-6 text-center"></i>
                            หมวดหมู่
                        </div>
                        <i id="categories-chevron" class="fas fa-chevron-down transition-transform"></i>
                    </div>
                    
                    <div id="categories-collapse" class="hidden bg-gray-50 rounded-lg mt-1 mb-2 py-2 pl-11 pr-3 space-y-1">
                        @forelse ($categories ?? [] as $category)
                            <a href="{{ route('category.show', $category->id) }}" class="block py-2 text-gray-700 hover:text-pink-600">
                                {{ $category->name }}
                            </a>
                        @empty
                            <p class="py-2 text-gray-500">ไม่มีหมวดหมู่</p>
                        @endforelse
                        <a href="{{ route('categories') }}" class="block py-2 text-pink-600 font-medium">
                            ดูทั้งหมด
                        </a>
                    </div>
                    
                    <a href="{{ route('promotions') }}" class="block px-3 py-3 rounded-lg {{ request()->routeIs('promotions') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-tags mr-2 w-6 text-center"></i>
                        โปรโมชั่น
                    </a>
                    
                    <a href="{{ route('how-to-rent') }}" class="block px-3 py-3 rounded-lg {{ request()->routeIs('how-to-rent') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-question-circle mr-2 w-6 text-center"></i>
                        วิธีการเช่า
                    </a>
                    
                    <a href="{{ route('wishlist') }}" class="block px-3 py-3 rounded-lg {{ request()->routeIs('wishlist') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-heart mr-2 w-6 text-center"></i>
                        รายการโปรด
                    </a>

                    @auth
                        <a href="{{ route('profile') }}" class="block px-3 py-3 rounded-lg {{ request()->routeIs('profile') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-user-circle mr-2 w-6 text-center"></i>
                            ข้อมูลส่วนตัว
                        </a>

                        <a href="{{ route('orders') }}" class="block px-3 py-3 rounded-lg {{ request()->routeIs('orders') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-shopping-basket mr-2 w-6 text-center"></i>
                            ประวัติการเช่า
                        </a>

                        <!-- Chat with Admin (Mobile Menu) -->
                        @if(Auth::user()->role !== 'admin')
                            <a href="{{ route('user.chat') }}" class="block px-3 py-3 rounded-lg {{ request()->routeIs('user.chat') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                <i class="fas fa-comment-dots mr-2 w-6 text-center"></i>
                                แชทกับแอดมิน
                            </a>
                        @endif
                        
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                <i class="fas fa-user-shield mr-2 w-6 text-center"></i>
                                แอดมิน
                            </a>
                            <a href="{{ route('admin.chats') }}" class="block px-3 py-3 rounded-lg text-gray-700 hover:bg-pink-50 hover:text-pink-600">
                                <i class="fas fa-comment-dots mr-2 w-6 text-center"></i>
                                แชททั้งหมด
                            </a>
                        @endif
                        
                        <div class="border-t border-gray-200 my-2"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-3 rounded-lg text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-sign-out-alt mr-2 w-6 text-center"></i>
                                ออกจากระบบ
                            </button>
                        </form>
                    @else
                        <div class="border-t border-gray-200 my-2"></div>
                        
                        <div class="px-3 py-3 space-y-2">
                            <a href="{{ route('login') }}" class="block w-full py-2 text-center rounded-lg font-medium text-pink-600 border border-pink-200 hover:bg-pink-50 transition duration-150">
                                เข้าสู่ระบบ
                            </a>
                            <a href="{{ route('register') }}" class="block w-full py-2 text-center rounded-lg font-medium text-white bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 transition duration-150">
                                สมัครสมาชิก
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Search Overlay -->
    <div id="search-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium">ค้นหา</h2>
                <button id="close-search" class="text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('search') }}" method="GET">
                <div class="relative">
                    <input type="text" name="query" placeholder="ค้นหาชุดในฝัน..." 
                           class="w-full py-3 pl-4 pr-10 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                    <button type="submit" class="absolute right-0 top-0 h-full px-4 flex items-center text-pink-500">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white pt-12 pb-6 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-pink-500 flex items-center justify-center mr-2">
                            <span class="text-white font-bold text-xl">P</span>
                        </div>
                        <span class="text-2xl font-bold text-white">
                            Dable P
                        </span>
                    </div>
                    <p class="text-gray-400 mb-6">บริการเช่าชุดคุณภาพดี โปรเด็ด ราคาโดน ที่จะทำให้คุณสวยได้ในทุกโอกาสพิเศษ</p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-pink-600 transition-colors duration-300">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-pink-600 transition-colors duration-300">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-pink-600 transition-colors duration-300">
                            <i class="fab fa-line text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-pink-600 transition-colors duration-300">
                            <i class="fab fa-tiktok text-lg"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">ข้อมูล</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('about') }}" class="hover:text-pink-400">เกี่ยวกับเรา</a></li>
                        <li><a href="{{ route('how-to-rent') }}" class="hover:text-pink-400">วิธีการเช่า</a></li>
                        <li><a href="{{ route('size-guide') }}" class="hover:text-pink-400">คู่มือการวัดไซส์</a></li>
                        <li><a href="{{ route('shipping') }}" class="hover:text-pink-400">การจัดส่ง</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-pink-400">คำถามที่พบบ่อย</a></li>
                        <li><a href="{{ route('articles') }}" class="hover:text-pink-400">บทความ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">นโยบาย</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('terms') }}" class="hover:text-pink-400">ข้อตกลงและเงื่อนไข</a></li>
                        <li><a href="{{ route('privacy-policy') }}" class="hover:text-pink-400">นโยบายความเป็นส่วนตัว</a></li>
                        <li><a href="{{ route('return-policy') }}" class="hover:text-pink-400">นโยบายการคืนชุด</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('mobile-menu-open');
            mobileMenu.classList.toggle('translate-x-full');
        });
        
        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('mobile-menu-open');
            mobileMenu.classList.add('translate-x-full');
        });
        
        const categoriesCollapseButton = document.getElementById('categories-collapse-button');
        const categoriesCollapse = document.getElementById('categories-collapse');
        const categoriesChevron = document.getElementById('categories-chevron');
        
        categoriesCollapseButton.addEventListener('click', () => {
            categoriesCollapse.classList.toggle('hidden');
            categoriesChevron.classList.toggle('rotate-180');
        });
        
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg', 'bg-opacity-95', 'py-2');
                nav.classList.remove('py-4');
            } else {
                nav.classList.remove('shadow-lg', 'bg-opacity-95', 'py-2');
                nav.classList.add('py-4');
            }
        });
        
        const searchButton = document.querySelector('.sm\\:hidden .fa-search');
        const searchOverlay = document.getElementById('search-overlay');
        const closeSearch = document.getElementById('close-search');
        
        searchButton.addEventListener('click', (e) => {
            e.preventDefault();
            searchOverlay.classList.remove('hidden');
        });
        
        closeSearch.addEventListener('click', () => {
            searchOverlay.classList.add('hidden');
        });
    </script>

    @stack('scripts')
</body>
</html>