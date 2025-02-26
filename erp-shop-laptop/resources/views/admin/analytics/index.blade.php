@extends('admin.layouts.app')

@section('title', 'Thống kê')

@section('content')
<div class="space-y-6">
    <!-- Thống kê tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Doanh thu -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-white">Doanh thu</h3>
                    <div class="h-12 w-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-2xl text-white"></i>
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-white">{{ number_format($totalRevenue) }}</span>
                    <span class="ml-2 text-white text-opacity-80">VNĐ</span>
                </div>
                <div class="mt-4 flex items-center text-white text-opacity-80">
                    <span class="{{ $revenueGrowth >= 0 ? 'text-green-300' : 'text-red-300' }}">
                        <i class="fas fa-{{ $revenueGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($revenueGrowth) }}%
                    </span>
                    <span class="ml-2">so với tháng trước</span>
                </div>
            </div>
        </div>

        <!-- Đơn hàng -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-white">Đơn hàng</h3>
                    <div class="h-12 w-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-2xl text-white"></i>
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-white">{{ number_format($totalOrders) }}</span>
                </div>
                <div class="mt-4 flex items-center text-white text-opacity-80">
                    <span class="{{ $orderGrowth >= 0 ? 'text-green-300' : 'text-red-300' }}">
                        <i class="fas fa-{{ $orderGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($orderGrowth) }}%
                    </span>
                    <span class="ml-2">so với tháng trước</span>
                </div>
            </div>
        </div>

        <!-- Khách hàng -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-white">Khách hàng</h3>
                    <div class="h-12 w-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-white">{{ number_format($totalCustomers) }}</span>
                </div>
                <div class="mt-4 flex items-center text-white text-opacity-80">
                    <span class="{{ $customerGrowth >= 0 ? 'text-green-300' : 'text-red-300' }}">
                        <i class="fas fa-{{ $customerGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($customerGrowth) }}%
                    </span>
                    <span class="ml-2">so với tháng trước</span>
                </div>
            </div>
        </div>

        <!-- Sản phẩm -->
        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-white">Sản phẩm</h3>
                    <div class="h-12 w-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-box text-2xl text-white"></i>
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-white">{{ number_format($totalProducts) }}</span>
                </div>
                <div class="mt-4 flex items-center text-white text-opacity-80">
                    <span class="{{ $productGrowth >= 0 ? 'text-green-300' : 'text-red-300' }}">
                        <i class="fas fa-{{ $productGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($productGrowth) }}%
                    </span>
                    <span class="ml-2">so với tháng trước</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Biểu đồ doanh thu</h3>
            <canvas id="revenueChart" class="w-full" height="300"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top sản phẩm bán chạy -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Top sản phẩm bán chạy</h3>
                <div class="space-y-4">
                    @foreach($topProducts as $product)
                    <div class="flex items-center">
                        <div class="h-16 w-16 flex-shrink-0">
                            @if(count($product->images) > 0)
                                <img class="h-16 w-16 rounded-lg object-cover" 
                                    src="{{ Storage::url($product->images[0]) }}" 
                                    alt="{{ $product->name }}">
                            @else
                                <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-box text-gray-500 text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ $product->name }}</h4>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-sm text-gray-500">Đã bán: {{ number_format($product->total_sold) }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($product->price) }} VNĐ</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Đơn hàng gần đây -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Đơn hàng gần đây</h3>
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-600">
                                {{ substr($order->customer_name, 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</h4>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($order->total) }} VNĐ</span>
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ $order->status === 'completed' ? 'Hoàn thành' : 
                                       ($order->status === 'pending' ? 'Chờ xử lý' : 'Đã hủy') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueData->pluck('month')) !!},
            datasets: [{
                label: 'Doanh thu',
                data: {!! json_encode($revenueData->pluck('revenue')) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 