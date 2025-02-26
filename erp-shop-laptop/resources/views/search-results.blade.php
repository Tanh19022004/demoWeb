@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm cho "' . $query . '"')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            Kết quả tìm kiếm cho "{{ $query }}"
        </h1>
        <p class="mt-2 text-sm text-gray-500">
            Tìm thấy {{ $products->total() }} sản phẩm
        </p>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <i class="fas fa-search text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy sản phẩm nào</h3>
            <p class="text-gray-500 mb-6">Vui lòng thử lại với từ khóa khác</p>
            <a href="{{ route('products') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Xem tất cả sản phẩm
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200">
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
                            <a href="{{ route('products', ['category' => $product->category->slug]) }}" 
                                class="hover:text-indigo-600">
                                {{ $product->category->name }}
                            </a>
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
                            
                            <div class="text-sm">
                                @if($product->quantity > 0)
                                    <span class="text-green-600">Còn hàng</span>
                                @else
                                    <span class="text-red-600">Hết hàng</span>
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
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->appends(['q' => $query])->links() }}
        </div>
    @endif
</div>
@endsection 