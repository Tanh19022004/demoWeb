@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 flex justify-between items-center border-b border-gray-200">
            <div>
                <h2 class="text-xl font-medium text-gray-900">
                    Đơn hàng #{{ $order->order_number }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Đặt ngày {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                @if($order->status === 'pending')
                    <form action="{{ route('admin.orders.process', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-truck mr-2"></i>Xử lý đơn hàng
                        </button>
                    </form>
                @endif
                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger" 
                            onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                            <i class="fas fa-times mr-2"></i>Hủy đơn hàng
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.orders.index') }}" class="btn btn-white">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Thông tin khách hàng -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Thông tin khách hàng</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Thông tin liên hệ</h4>
                            <p class="text-sm text-gray-900">{{ $order->customer_name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->customer_email }}</p>
                            <p class="text-sm text-gray-500">{{ $order->customer_phone }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng</h4>
                            <p class="text-sm text-gray-900">{{ $order->shipping_address }}</p>
                            <p class="text-sm text-gray-500">{{ $order->shipping_city }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Danh sách sản phẩm</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center">
                            <div class="h-16 w-16 flex-shrink-0">
                                @if(count($item->product->images) > 0)
                                    <img class="h-16 w-16 rounded-lg object-cover" 
                                        src="{{ Storage::url($item->product->images[0]) }}" 
                                        alt="{{ $item->product->name }}">
                                @else
                                    <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-box text-gray-500 text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($item->price) }} VNĐ
                                        </p>
                                        <p class="text-sm text-gray-500">x{{ $item->quantity }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin thanh toán -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Thông tin thanh toán</h3>
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tạm tính</span>
                        <span class="text-gray-900">{{ number_format($order->subtotal) }} VNĐ</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Giảm giá</span>
                        <span class="text-green-600">-{{ number_format($order->discount) }} VNĐ</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Phí vận chuyển</span>
                        <span class="text-gray-900">{{ number_format($order->shipping_fee) }} VNĐ</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-base font-medium text-gray-900">Tổng cộng</span>
                            <span class="text-base font-medium text-gray-900">{{ number_format($order->total) }} VNĐ</span>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <div class="space-y-2">
                           
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Trạng thái</span>
                                <span class="font-medium {{ 
                                    $order->status === 'completed' ? 'text-green-600' : 
                                    ($order->status === 'pending' ? 'text-yellow-600' : 
                                    ($order->status === 'processing' ? 'text-blue-600' : 'text-red-600')) 
                                }}">
                                    {{ $order->status === 'completed' ? 'Hoàn thành' : 
                                       ($order->status === 'pending' ? 'Chờ xử lý' : 
                                       ($order->status === 'processing' ? 'Đang xử lý' : 'Đã hủy')) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 