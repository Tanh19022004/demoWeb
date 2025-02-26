@extends('admin.layouts.app')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Thông tin người dùng -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-medium text-gray-900">Thông tin người dùng</h2>
        </div>
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="w-24 h-24 bg-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <h3 class="text-xl font-medium text-gray-900">{{ $user->name }}</h3>
                <p class="text-gray-500">{{ $user->email }}</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Số điện thoại</label>
                    <p class="mt-1">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Địa chỉ</label>
                    <p class="mt-1">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Ngày tham gia</label>
                    <p class="mt-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Trạng thái</label>
                    <p class="mt-1">
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Hoạt động' : 'Khóa' }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-2"></i>Chỉnh sửa
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-2"></i>Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-medium text-gray-900">Đơn hàng gần đây</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr>
                                <td class="font-medium text-gray-900">{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($order->total) }}đ</td>
                                <td>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">Chưa có đơn hàng nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

        <!-- Đánh giá gần đây -->
        <div class="bg-white rounded-lg shadow-sm mt-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-medium text-gray-900">Đánh giá gần đây</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($reviews as $review)
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <a href="{{ route('admin.products.show', $review->product) }}" class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                                    {{ $review->product->name }}
                                </a>
                                <div class="flex items-center mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="mt-2 text-gray-600">{{ $review->comment }}</p>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">Chưa có đánh giá nào</div>
                @endforelse
            </div>
            @if($reviews->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.btn-sm {
    @apply p-2;
}
</style>
@endsection 