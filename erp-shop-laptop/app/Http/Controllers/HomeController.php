<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }

    public function products(Request $request)
    {
        $query = Product::where('is_active', true);

        // Lọc theo danh mục
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Lọc theo giá
        if ($request->has('price')) {
            switch ($request->price) {
                case 'under-10':
                    $query->where('price', '<', 10000000);
                    break;
                case '10-20':
                    $query->whereBetween('price', [10000000, 20000000]);
                    break;
                case '20-30':
                    $query->whereBetween('price', [20000000, 30000000]);
                    break;
                case 'over-30':
                    $query->where('price', '>', 30000000);
                    break;
            }
        }

        // Sắp xếp
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price-asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'name-asc':
                    $query->orderBy('name', 'asc');
                    break;
            }
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('products', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'reviews.user'])
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $categories = Category::where('is_active', true)->get();

        return view('product-detail', compact('product', 'relatedProducts', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('brand', 'like', "%{$query}%");
            })
            ->paginate(12);
        $categories = Category::where('is_active', true)->get();
            
        return view('search-results', compact('products', 'query', 'categories'));
    }
} 