@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 flex justify-between items-center border-b border-gray-200">
        <h2 class="text-xl font-medium text-gray-900">Quản lý đơn hàng</h2>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" id="search" placeholder="Tìm kiếm đơn hàng..." 
                    class="form-input pr-10 rounded-full">
                <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <select id="status" class="form-select rounded-full">
                <option value="">Tất cả trạng thái</option>
                <option value="pending">Chờ xử lý</option>
                <option value="processing">Đang xử lý</option>
                <option value="completed">Hoàn thành</option>
                <option value="cancelled">Đã hủy</option>
            </select>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="p-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-600 mb-4">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Chưa có đơn hàng nào</h3>
            <p class="text-gray-500">Đơn hàng mới sẽ xuất hiện ở đây</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                            <div class="text-sm text-gray-500">{{ $order->items_count }} sản phẩm</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600">
                                        {{ substr($order->customer_name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                                    @if($order->customer_phone)
                                        <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ number_format($order->total) }} VNĐ</div>
                            @if($order->discount > 0)
                                <div class="text-sm text-green-600">-{{ number_format($order->discount) }} VNĐ</div>
                            @endif
                        </td>
                      
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                   'bg-red-100 text-red-800')) }}">
                                {{ $order->status === 'completed' ? 'Hoàn thành' : 
                                   ($order->status === 'pending' ? 'Chờ xử lý' : 
                                   ($order->status === 'processing' ? 'Đang xử lý' : 'Đã hủy')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($order->status === 'pending')
                                <form action="{{ route('admin.orders.process', $order) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-truck"></i>
                                    </button>
                                </form>
                            @endif
                            @if($order->status === 'processing')
                                <form action="{{ route('admin.orders.complete', $order) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                        onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    let searchTimeout;

    function filterOrders() {
        const searchQuery = searchInput.value;
        const status = statusSelect.value;
        window.location.href = `{{ route('admin.orders.index') }}?search=${searchQuery}&status=${status}`;
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterOrders, 500);
    });

    statusSelect.addEventListener('change', filterOrders);
});
</script>
@endpush
@endsection 