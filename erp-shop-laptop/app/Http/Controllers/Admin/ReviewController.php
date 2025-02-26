<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'product'])
            ->latest()
            ->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function pending()
    {
        $reviews = Review::with(['user', 'product'])
            ->where('is_approved', false)
            ->latest()
            ->paginate(20);
        return view('admin.reviews.pending', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Đánh giá đã được duyệt.');
    }

    public function reject(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Đánh giá đã bị từ chối và xóa.');
    }

    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'reviews' => 'required|array',
            'reviews.*' => 'exists:reviews,id'
        ]);

        Review::whereIn('id', $validated['reviews'])
            ->update(['is_approved' => true]);

        return back()->with('success', 'Các đánh giá đã được duyệt.');
    }

    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'reviews' => 'required|array',
            'reviews.*' => 'exists:reviews,id'
        ]);

        Review::whereIn('id', $validated['reviews'])->delete();

        return back()->with('success', 'Các đánh giá đã bị từ chối và xóa.');
    }
} 