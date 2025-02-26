<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - ERP Shop Laptop</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-2xl font-bold text-indigo-600">ERP Shop</span>
                </a>

                <!-- Search -->
                <div class="flex-1 max-w-xl px-4">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit" class="absolute right-3 top-2">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Navigation -->
                <nav class="flex items-center space-x-8">
                    <a href="{{ route('products') }}" class="text-gray-600 hover:text-indigo-600">Sản phẩm</a>
                    
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(session()->has('cart') && count(session('cart')) > 0)
                            <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <!-- User Menu -->
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center text-gray-600 hover:text-indigo-600">
                                <span class="mr-2">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-500 hover:text-white">
                                    Đơn hàng của tôi
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-500 hover:text-white">
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Đăng ký</a>
                    @endauth
                </nav>
            </div>

            <!-- Categories Menu -->
            <div class="border-t border-gray-100">
                <nav class="flex items-center overflow-x-auto py-4 scrollbar-hide">
                    <a href="{{ route('products') }}" 
                        class="flex items-center px-4 py-2 rounded-full text-sm font-medium {{ request()->routeIs('products') && !request()->has('category') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <i class="fas fa-th-large mr-2"></i>
                        Tất cả sản phẩm
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('products', ['category' => $category->slug]) }}" 
                            class="flex items-center px-4 py-2 ml-4 rounded-full text-sm font-medium 
                                {{ request()->get('category') == $category->slug ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                            <span>{{ $category->name }}</span>
                            @if($category->products_count > 0)
                                <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs {{ request()->get('category') == $category->slug ? 'bg-indigo-500 text-white' : '' }}">
                                    {{ $category->products_count }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Về chúng tôi</h3>
                    <p class="text-gray-400">
                        ERP Shop - Hệ thống bán lẻ laptop uy tín, chất lượng với đa dạng sản phẩm và dịch vụ hậu mãi tốt nhất.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên kết</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Trang chủ</a></li>
                        <li><a href="{{ route('products') }}" class="text-gray-400 hover:text-white">Sản phẩm</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Tin tức</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Chính sách</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Chính sách bảo hành</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Chính sách đổi trả</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Chính sách vận chuyển</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Chính sách bảo mật</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liên hệ</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            123 Đường ABC, Quận XYZ, TP.HCM
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            0123.456.789
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            info@erpshop.com
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} ERP Shop. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html> 