@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Chi tiết đơn hàng #{{ $order->order_number }}</h1>
        <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-900">
            Quay lại danh sách
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Thông tin đơn hàng -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600">Mã đơn hàng:</span>
                    <span class="font-medium">{{ $order->order_number }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Ngày đặt:</span>
                    <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Tổng tiền:</span>
                    <span class="font-medium">{{ number_format($order->total) }}đ</span>
                </div>
                <div>
                    <span class="text-gray-600">Trạng thái:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @else bg-red-100 text-red-800
                        @endif">
                        @switch($order->status)
                            @case('pending')
                                Chờ xử lý
                                @break
                            @case('processing')
                                Đang xử lý
                                @break
                            @case('completed')
                                Hoàn thành
                                @break
                            @case('cancelled')
                                Đã hủy
                                @break
                        @endswitch
                    </span>
                </div>
            </div>

            @if($order->status === 'pending')
                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                        onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                        Hủy đơn hàng
                    </button>
                </form>
            @endif
        </div>

        <!-- Thông tin giao hàng -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Thông tin giao hàng</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600">Người nhận:</span>
                    <span class="font-medium">{{ $order->shipping_name }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Số điện thoại:</span>
                    <span class="font-medium">{{ $order->shipping_phone }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Địa chỉ:</span>
                    <span class="font-medium">{{ $order->shipping_address }}</span>
                </div>
                @if($order->notes)
                    <div>
                        <span class="text-gray-600">Ghi chú:</span>
                        <span class="font-medium">{{ $order->notes }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Thống kê -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Thống kê</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600">Tổng sản phẩm:</span>
                    <span class="font-medium">{{ $order->items->sum('quantity') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Tổng tiền hàng:</span>
                    <span class="font-medium">{{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; })) }}đ</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết sản phẩm -->
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Chi tiết sản phẩm</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            @if(count($item->product->images) > 0)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($item->product->images[0]) }}" alt="">
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                            <div class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($item->price) }}đ
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($item->price * $item->quantity) }}đ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 