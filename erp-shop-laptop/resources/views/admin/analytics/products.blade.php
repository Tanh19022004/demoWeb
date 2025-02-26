@extends('admin.layouts.app')

@section('title', 'Phân tích sản phẩm')

@section('content')
<div class="space-y-6">
    <!-- Thống kê sản phẩm -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Tổng sản phẩm -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tổng sản phẩm</h3>
                <div class="h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-2xl text-indigo-600"></i>
                </div>
            </div>
            <div class="flex items-baseline">
                <span class="text-3xl font-bold text-gray-900">{{ number_format($totalProducts) }}</span>
            </div>
            <div class="mt-4 flex items-center text-gray-500">
                <span class="{{ $productGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas fa-{{ $productGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                    {{ abs($productGrowth) }}%
                </span>
                <span class="ml-2">so với tháng trước</span>
            </div>
        </div>

        <!-- Sản phẩm hết hàng -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Hết hàng</h3>
                <div class="h-12 w-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
            </div>
            <div class="flex items-baseline">
                <span class="text-3xl font-bold text-gray-900">{{ number_format($outOfStockProducts) }}</span>
            </div>
            <div class="mt-4 flex items-center text-gray-500">
                <span class="text-red-600">{{ number_format($outOfStockPercentage, 1) }}%</span>
                <span class="ml-2">tổng sản phẩm</span>
            </div>
        </div>

        <!-- Sản phẩm sắp hết -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Sắp hết hàng</h3>
                <div class="h-12 w-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-battery-quarter text-2xl text-yellow-600"></i>
                </div>
            </div>
            <div class="flex items-baseline">
                <span class="text-3xl font-bold text-gray-900">{{ number_format($lowStockProducts) }}</span>
            </div>
            <div class="mt-4 flex items-center text-gray-500">
                <span class="text-yellow-600">{{ number_format($lowStockPercentage, 1) }}%</span>
                <span class="ml-2">tổng sản phẩm</span>
            </div>
        </div>

        <!-- Tổng danh mục -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Danh mục</h3>
                <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-folder text-2xl text-green-600"></i>
                </div>
            </div>
            <div class="flex items-baseline">
                <span class="text-3xl font-bold text-gray-900">{{ number_format($totalCategories) }}</span>
            </div>
            <div class="mt-4 flex items-center text-gray-500">
                <span class="text-green-600">{{ number_format($avgProductsPerCategory, 1) }}</span>
                <span class="ml-2">sản phẩm/danh mục</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Biểu đồ phân bố sản phẩm theo danh mục -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Phân bố sản phẩm theo danh mục</h3>
                <canvas id="categoryChart" class="w-full" height="300"></canvas>
            </div>
        </div>

        <!-- Biểu đồ xu hướng tồn kho -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Xu hướng tồn kho</h3>
                <canvas id="stockTrendChart" class="w-full" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Bảng sản phẩm bán chạy -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Top sản phẩm bán chạy</h3>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Đã bán</th>
                            <th>Doanh thu</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topSellingProducts as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        @if(count($product->images) > 0)
                                            <img class="h-10 w-10 rounded-lg object-cover" 
                                                src="{{ Storage::url($product->images[0]) }}" 
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-box text-gray-500"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($product->total_sold) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($product->total_revenue) }} VNĐ
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($product->stock) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $product->stock > $product->low_stock_threshold ? 'bg-green-100 text-green-800' : 
                                       ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $product->stock > $product->low_stock_threshold ? 'Còn hàng' : 
                                       ($product->stock > 0 ? 'Sắp hết' : 'Hết hàng') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ phân bố sản phẩm theo danh mục
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryData->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($categoryData->pluck('product_count')) !!},
                backgroundColor: [
                    '#6366f1', '#10b981', '#f59e0b', '#ef4444',
                    '#8b5cf6', '#14b8a6', '#f97316', '#ec4899'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Biểu đồ xu hướng tồn kho
    const stockCtx = document.getElementById('stockTrendChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($stockTrendData->pluck('month')) !!},
            datasets: [{
                label: 'Tồn kho',
                data: {!! json_encode($stockTrendData->pluck('stock')) !!},
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
                            return new Intl.NumberFormat('vi-VN').format(value);
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