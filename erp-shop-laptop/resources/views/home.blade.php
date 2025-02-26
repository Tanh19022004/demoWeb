@extends('layouts.app')

@section('title', 'Trang chủ')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
<style>
    .swiper-button-next, .swiper-button-prev {
        color: #4f46e5;
    }
    .swiper-pagination-bullet-active {
        background: #4f46e5;
    }
</style>
@endpush

@section('content')
<!-- Hero Slider -->
<div class="swiper hero-slider">
    <div class="swiper-wrapper">
        <div class="swiper-slide relative">
            <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45" alt="Laptop Banner" class="w-full h-[500px] object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center">
                <div class="container mx-auto px-4">
                    <div class="max-w-xl text-white">
                        <h1 class="text-4xl font-bold mb-4">Laptop Gaming Cao Cấp</h1>
                        <p class="text-lg mb-6">Trải nghiệm gaming đỉnh cao với các dòng laptop mới nhất</p>
                        <a href="{{ route('products') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                            Khám phá ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-slide relative">
            <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302" alt="Office Laptop Banner" class="w-full h-[500px] object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center">
                <div class="container mx-auto px-4">
                    <div class="max-w-xl text-white">
                        <h1 class="text-4xl font-bold mb-4">Laptop Văn Phòng</h1>
                        <p class="text-lg mb-6">Hiệu suất cao, thiết kế mỏng nhẹ, phù hợp cho công việc</p>
                        <a href="{{ route('products') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                            Xem thêm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>

<!-- Categories Section -->
<div class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Danh Mục Sản Phẩm</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('products', ['category' => $category->slug]) }}" 
                class="group bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-200 transition-colors">
                    <i class="fas fa-laptop text-2xl text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $category->name }}</h3>
                <p class="text-sm text-gray-500">{{ $category->products_count }} sản phẩm</p>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="py-12">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Sản Phẩm Nổi Bật</h2>
            <a href="{{ route('products') }}" class="text-indigo-600 hover:text-indigo-700 flex items-center">
                Xem tất cả
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="swiper featured-products">
            <div class="swiper-wrapper">
                @foreach($featuredProducts as $product)
                <div class="swiper-slide">
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow">
                        <a href="{{ route('product.show', $product->slug) }}" class="block relative">
                            @if(count($product->images) > 0)
                                <img src="{{ Storage::url($product->images[0]) }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-48 object-cover rounded-t-lg">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                            
                            @if($product->sale_price)
                                <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                                    Giảm {{ number_format((($product->price - $product->sale_price) / $product->price) * 100, 0) }}%
                                </div>
                            @endif
                        </a>

                        <div class="p-4">
                            <a href="{{ route('product.show', $product->slug) }}" class="block">
                                <h3 class="text-lg font-medium text-gray-900 hover:text-indigo-600 truncate">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $product->category->name }}
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-lg font-bold text-red-600">
                                            {{ number_format($product->sale_price) }}đ
                                        </span>
                                        <span class="ml-2 text-sm text-gray-500 line-through">
                                            {{ number_format($product->price) }}đ
                                        </span>
                                    @else
                                        <span class="text-lg font-bold text-gray-900">
                                            {{ number_format($product->price) }}đ
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" 
                                    class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors"
                                    {{ $product->quantity == 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Thêm vào giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Chính sách & Dịch vụ</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                <div class="relative p-8 pt-36">
                    <div class="absolute top-12 right-8">
                        <img src="https://cdn-icons-png.flaticon.com/512/2518/2518048.png" 
                            alt="Free Shipping" 
                            class="w-20 h-20 object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Miễn phí vận chuyển</h3>
                        <p class="text-gray-600">Áp dụng cho đơn hàng từ 20 triệu đồng</p>
                        <div class="mt-4 flex items-center text-indigo-600">
                            <span class="text-sm font-medium">Tìm hiểu thêm</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-blue-500 to-indigo-600"></div>
                <div class="relative p-8 pt-36">
                    <div class="absolute top-12 right-8">
                        <img src="https://cdn-icons-png.flaticon.com/512/2910/2910791.png" 
                            alt="Warranty" 
                            class="w-20 h-20 object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Bảo hành chính hãng</h3>
                        <p class="text-gray-600">Bảo hành 12 tháng toàn quốc</p>
                        <div class="mt-4 flex items-center text-blue-600">
                            <span class="text-sm font-medium">Xem chính sách</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-green-500 to-teal-600"></div>
                <div class="relative p-8 pt-36">
                    <div class="absolute top-12 right-8">
                        <img src="https://cdn-icons-png.flaticon.com/512/1554/1554264.png" 
                            alt="Return Policy" 
                            class="w-20 h-20 object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Đổi trả miễn phí</h3>
                        <p class="text-gray-600">Đổi trả trong vòng 7 ngày</p>
                        <div class="mt-4 flex items-center text-green-600">
                            <span class="text-sm font-medium">Chi tiết</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Slider
    new Swiper('.hero-slider', {
        loop: true,
        autoplay: {
            delay: 5000,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    // Featured Products Slider
    new Swiper('.featured-products', {
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
        },
    });
});
</script>
@endpush 