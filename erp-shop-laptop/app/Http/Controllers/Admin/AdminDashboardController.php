<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total');
        
        // Đơn hàng gần đây
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Sản phẩm bán chạy
        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        // Đánh giá gần đây
        $recentReviews = Review::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        // Thống kê doanh thu theo tháng
        $monthlyRevenue = Order::where('status', 'completed')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total) as revenue'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts', 
            'totalCustomers',
            'totalRevenue',
            'recentOrders',
            'topProducts',
            'recentReviews',
            'monthlyRevenue'
        ));
    }
} 