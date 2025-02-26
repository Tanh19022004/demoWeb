<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('admin.analytics.index', [
            'totalRevenue' => $this->getTotalRevenue(),
            'monthlyRevenue' => $this->getMonthlyRevenue(),
            'topProducts' => $this->getTopProducts(),
            'topCustomers' => $this->getTopCustomers()
        ]);
    }

    public function sales()
    {
        $startDate = request('start_date', Carbon::now()->startOfMonth());
        $endDate = request('end_date', Carbon::now()->endOfMonth());

        $sales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->groupBy('date')
            ->get();

        return view('admin.analytics.sales', compact('sales'));
    }

    public function products()
    {
        $products = Product::withCount(['orderItems as total_sold' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                });
            }])
            ->withSum(['orderItems as total_revenue' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                });
            }], 'total')
            ->orderBy('total_sold', 'desc')
            ->paginate(10);

        return view('admin.analytics.products', compact('products'));
    }

    public function customers()
    {
        $customers = User::where('role', 'customer')
            ->withCount(['orders as total_orders' => function($query) {
                $query->where('status', 'completed');
            }])
            ->withSum(['orders as total_spent' => function($query) {
                $query->where('status', 'completed');
            }], 'total')
            ->orderBy('total_spent', 'desc')
            ->paginate(10);

        return view('admin.analytics.customers', compact('customers'));
    }

    private function getTotalRevenue()
    {
        return Order::where('status', 'completed')->sum('total');
    }

    private function getMonthlyRevenue()
    {
        return Order::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('month')
            ->get();
    }

    private function getTopProducts($limit = 5)
    {
        return Product::withCount(['orderItems as total_sold' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                });
            }])
            ->orderBy('total_sold', 'desc')
            ->take($limit)
            ->get();
    }

    private function getTopCustomers($limit = 5)
    {
        return User::where('role', 'customer')
            ->withSum(['orders as total_spent' => function($query) {
                $query->where('status', 'completed');
            }], 'total')
            ->orderBy('total_spent', 'desc')
            ->take($limit)
            ->get();
    }
} 