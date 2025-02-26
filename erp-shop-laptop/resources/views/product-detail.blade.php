@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Product Images -->
                <div x-data="{ activeImage: '{{ Storage::url($product->images[0]) }}' }">
                    <div class="mb-4">
                        <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-lg">
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($product->images as $image)
                            <button @click="activeImage = '{{ Storage::url($image) }}'" 
                                class="focus:outline-none">
                                <img src="{{ Storage::url($image) }}" alt="{{ $product->name }}" 
                                    class="w-full h-24 object-cover rounded-lg hover:opacity-75 transition-opacity"
                                    :class="{ 'ring-2 ring-indigo-500': activeImage === '{{ Storage::url($image) }}' }">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Product Info -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <p class="mt-2 text-gray-500">Thương hiệu: {{ $product->brand }}</p>
                    
                    <div class="mt-4">
                        @if($product->sale_price)
                            <span class="text-3xl font-bold text-red-600">{{ number_format($product->sale_price) }}đ</span>
                            <span class="ml-2 text-xl text-gray-500 line-through">{{ number_format($product->price) }}đ</span>
                        @else
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($product->price) }}đ</span>
                        @endif
                    </div>

                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-gray-900">Mô tả sản phẩm</h2>
                        <div class="mt-2 text-gray-600 space-y-4">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>

                    <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST" class="mt-8">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="flex items-center">
                            <label for="quantity" class="mr-4 text-gray-700">Số lượng:</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->quantity }}"
                                class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="mt-4 w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                            Thêm vào giỏ hàng
                        </button>
                    </form>
                </div>
            </div>

            <!-- Specifications -->
            <div class="mt-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Thông số kỹ thuật</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($product->specifications as $key => $value)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500">{{ ucfirst($key) }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $value }}</dd>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Sản phẩm liên quan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow">
                                <a href="{{ route('product.show', $relatedProduct->slug) }}">
                                    @if(count($relatedProduct->images) > 0)
                                        <img src="{{ Storage::url($relatedProduct->images[0]) }}" alt="{{ $relatedProduct->name }}" 
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
                                    <a href="{{ route('product.show', $relatedProduct->slug) }}" class="block">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $relatedProduct->name }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ $relatedProduct->brand }}</p>
                                    </a>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div>
                                            @if($relatedProduct->sale_price)
                                                <span class="text-lg font-bold text-red-600">{{ number_format($relatedProduct->sale_price) }}đ</span>
                                                <span class="ml-2 text-sm text-gray-500 line-through">{{ number_format($relatedProduct->price) }}đ</span>
                                            @else
                                                <span class="text-lg font-bold text-gray-900">{{ number_format($relatedProduct->price) }}đ</span>
                                            @endif
                                        </div>
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
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
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Star rating interaction
        const ratingInputs = document.querySelectorAll('input[name="rating"]');
        const ratingLabels = document.querySelectorAll('label[for^="rating-"]');

        ratingInputs.forEach((input, index) => {
            input.addEventListener('change', () => {
                ratingLabels.forEach((label, i) => {
                    const star = label.querySelector('svg');
                    if (i <= index) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            });
        });

        // Add to cart handling
        document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: this.querySelector('input[name="product_id"]').value,
                    quantity: this.querySelector('input[name="quantity"]').value
                })
            })
            .then(response => response.json())
            .then(data => {
                // Hiển thị thông báo
                alert(data.message);
                
                // Cập nhật số lượng trong giỏ hàng
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                }
                
                // Tải lại trang sau 1 giây
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
            });
        });
    </script>
    @endpush
@endsection 