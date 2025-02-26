@extends('admin.layouts.app')

@section('title', 'Đánh giá chờ duyệt')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 flex justify-between items-center border-b border-gray-200">
        <h2 class="text-xl font-medium text-gray-900">Đánh giá chờ duyệt</h2>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-primary">
            <i class="fas fa-list mr-2"></i>Tất cả đánh giá
        </a>
    </div>

    @if($pendingReviews->isEmpty())
        <div class="p-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 mb-4">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Không có đánh giá nào chờ duyệt</h3>
            <p class="text-gray-500">Tất cả đánh giá đã được xử lý</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Khách hàng</th>
                        <th>Đánh giá</th>
                        <th>Nội dung</th>
                        <th>Ngày đánh giá</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pendingReviews as $review)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    @if(count($review->product->images) > 0)
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                            src="{{ Storage::url($review->product->images[0]) }}" 
                                            alt="{{ $review->product->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-box text-gray-500"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $review->product->name }}</div>
                                    <div class="text-sm text-gray-500">SKU: {{ $review->product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $review->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $review->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($review->comment, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $review->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pendingReviews->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendingReviews->links() }}
            </div>
        @endif
    @endif
</div>
@endsection 