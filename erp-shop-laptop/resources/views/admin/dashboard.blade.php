@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Thống kê tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Tổng đơn hàng -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-100">Tổng đơn hàng</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ number_format($totalOrders) }}</p>
                    </div>
                    <div class="bg-purple-500 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-shopping-cart text-2xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.orders.index') }}" class="text-purple-100 hover:text-white text-sm font-medium flex items-center">
                        Xem chi tiết
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Doanh thu -->
        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Doanh thu</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ number_format($totalRevenue, 0, ',', '.') }}đ</p>
                    </div>
                    <div class="bg-green-500 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-dollar-sign text-2xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.analytics.sales') }}" class="text-green-100 hover:text-white text-sm font-medium flex items-center">
                        Xem chi tiết
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sản phẩm -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100">Sản phẩm</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ number_format($totalProducts) }}</p>
                    </div>
                    <div class="bg-blue-500 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-box text-2xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.products.index') }}" class="text-blue-100 hover:text-white text-sm font-medium flex items-center">
                        Xem chi tiết
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Khách hàng -->
        <div class="bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-100">Khách hàng</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ number_format($totalCustomers) }}</p>
                    </div>
                    <div class="bg-yellow-500 bg-opacity-30 rounded-full p-3">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.index') }}" class="text-yellow-100 hover:text-white text-sm font-medium flex items-center">
                        Xem chi tiết
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Biểu đồ doanh thu -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Doanh thu theo tháng</h3>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.analytics.sales') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Xem chi tiết</a>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top sản phẩm bán chạy -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Top sản phẩm bán chạy</h3>
                    <a href="{{ route('admin.analytics.products') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Xem tất cả</a>
                </div>
                <div class="space-y-4">
                    @foreach($topProducts as $product)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 rounded-lg p-3">
                                <i class="fas fa-box text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-500">Đã bán: {{ number_format($product->order_items_count) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.show', $product) }}" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Đơn hàng gần đây -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Đơn hàng gần đây</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Xem tất cả</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ substr($order->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ number_format($order->total, 0, ',', '.') }}đ</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Đánh giá gần đây -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Đánh giá gần đây</h3>
                    <a href="{{ route('admin.reviews.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">Xem tất cả</a>
                </div>
                <div class="space-y-4">
                    @foreach($recentReviews as $review)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-600">
                                    {{ substr($review->user->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $review->user->name }}</div>
                                <div class="flex items-center mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="text-xs text-gray-500 ml-2">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">{{ Str::limit($review->comment, 100) }}</p>
                        <div class="mt-2">
                            <a href="{{ route('admin.products.show', $review->product) }}" class="text-xs text-indigo-600 hover:text-indigo-900">
                                {{ $review->product->name }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const monthlyRevenue = @json($monthlyRevenue);
    
    const labels = monthlyRevenue.map(item => {
        const months = ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'];
        return months[item.month - 1];
    });
    
    const data = monthlyRevenue.map(item => item.revenue);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: data,
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
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
                            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush 