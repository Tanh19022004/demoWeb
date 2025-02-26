@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Cart Items -->
        <div class="flex-1">
            <h1 class="text-2xl font-semibold mb-6">Giỏ hàng của bạn</h1>

            @if(empty($cart))
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-500 mb-4">Giỏ hàng của bạn đang trống</p>
                    <a href="{{ route('products') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                        Tiếp tục mua sắm
                    </a>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cart as $id => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($item['image'])
                                                <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" 
                                                    class="h-16 w-16 object-cover rounded-lg">
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($item['price']) }}đ</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <button type="button" onclick="updateQuantity({{ $id }}, -1)"
                                                class="text-gray-500 hover:text-gray-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <input type="number" value="{{ $item['quantity'] }}" min="1"
                                                class="mx-2 w-16 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                onchange="updateQuantity({{ $id }}, this.value - {{ $item['quantity'] }})">
                                            <button type="button" onclick="updateQuantity({{ $id }}, 1)"
                                                class="text-gray-500 hover:text-gray-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($item['price'] * $item['quantity']) }}đ</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button type="button" onclick="removeItem({{ $id }})"
                                            class="text-red-600 hover:text-red-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-900"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm?')">
                            Xóa giỏ hàng
                        </button>
                    </form>
                    <div class="text-right">
                        <p class="text-lg font-medium text-gray-900">
                            Tổng tiền: {{ number_format(collect($cart)->sum(function($item) { return $item['price'] * $item['quantity']; })) }}đ
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Form -->
        @if(!empty($cart))
            <div class="w-full md:w-96">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Thông tin đặt hàng</h2>
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="shipping_name" class="block text-sm font-medium text-gray-700 mb-2">Họ tên</label>
                            <input type="text" name="shipping_name" id="shipping_name" 
                                value="{{ old('shipping_name', auth()->user()->name ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required>
                            @error('shipping_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                            <input type="text" name="shipping_phone" id="shipping_phone" 
                                value="{{ old('shipping_phone') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required>
                            @error('shipping_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                            Đặt hàng
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function updateQuantity(productId, change) {
            const input = event.target.closest('tr').querySelector('input');
            const newQuantity = parseInt(input.value) + change;
            
            if (newQuantity < 1) return;

            fetch('{{ route('cart.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: newQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    location.reload();
                }
            });
        }

        function removeItem(productId) {
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;

            fetch('{{ route('cart.remove') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    location.reload();
                }
            });
        }
    </script>
    @endpush
@endsection 