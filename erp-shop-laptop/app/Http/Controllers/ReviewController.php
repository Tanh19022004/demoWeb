<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000'
        ]);

        // Kiểm tra xem người dùng đã mua sản phẩm chưa
        $hasPurchased = $product->orderItems()
            ->whereHas('order', function ($query) {
                $query->where('user_id', auth()->id())
                    ->where('status', 'completed');
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Bạn cần mua sản phẩm trước khi đánh giá.');
        }

        // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
        $existingReview = $product->reviews()
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        $review = new Review($validated);
        $review->user_id = auth()->id();
        $review->product_id = $product->id;
        $review->is_approved = false;
        $review->save();

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm. Đánh giá của bạn sẽ được hiển thị sau khi được duyệt.');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000'
        ]);

        $review->update($validated);

        return back()->with('success', 'Đánh giá của bạn đã được cập nhật.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Đánh giá đã được xóa.');
    }
} 