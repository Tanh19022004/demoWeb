@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Filters -->
        <div class="w-full md:w-64 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Bộ lọc</h2>
            <form action="{{ route('products') }}" method="GET">
                <!-- Categories -->
                <div class="mb-6">
                    <h3 class="font-medium mb-2">Danh mục</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                            <label class="flex items-center">
                                <input type="radio" name="category" value="{{ $category->slug }}"
                                    {{ request('category') == $category->slug ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <h3 class="font-medium mb-2">Khoảng giá</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="price" value="under-10"
                                {{ request('price') == 'under-10' ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-gray-700">Dưới 10 triệu</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price" value="10-20"
                                {{ request('price') == '10-20' ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-gray-700">10 - 20 triệu</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price" value="20-30"
                                {{ request('price') == '20-30' ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-gray-700">20 - 30 triệu</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price" value="over-30"
                                {{ request('price') == 'over-30' ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-gray-700">Trên 30 triệu</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Lọc sản phẩm
                </button>
            </form>
        </div>

        <!-- Products -->
        <div class="flex-1">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Sản phẩm</h1>
                
                <!-- Sort -->
                <div class="flex items-center">
                    <span class="mr-2 text-gray-600">Sắp xếp:</span>
                    <select onchange="window.location.href=this.value" 
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                            {{ request('sort') == 'newest' ? 'selected' : '' }}>
                            Mới nhất
                        </option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-asc']) }}"
                            {{ request('sort') == 'price-asc' ? 'selected' : '' }}>
                            Giá tăng dần
                        </option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-desc']) }}"
                            {{ request('sort') == 'price-desc' ? 'selected' : '' }}>
                            Giá giảm dần
                        </option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'name-asc']) }}"
                            {{ request('sort') == 'name-asc' ? 'selected' : '' }}>
                            Tên A-Z
                        </option>
                    </select>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-500">Không tìm thấy sản phẩm nào phù hợp với tiêu chí tìm kiếm.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow">
                            <a href="{{ route('product.show', $product->slug) }}">
                                @if(count($product->images) > 0)
                                    <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" 
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            <div class="p-4">
                                <a href="{{ route('product.show', $product->slug) }}" class="block">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $product->brand }}</p>
                                </a>
                                <div class="mt-4 flex items-center justify-between">
                                    <div>
                                        @if($product->sale_price)
                                            <span class="text-lg font-bold text-red-600">{{ number_format($product->sale_price) }}đ</span>
                                            <span class="ml-2 text-sm text-gray-500 line-through">{{ number_format($product->price) }}đ</span>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">{{ number_format($product->price) }}đ</span>
                                        @endif
                                    </div>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                            Thêm vào giỏ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection 