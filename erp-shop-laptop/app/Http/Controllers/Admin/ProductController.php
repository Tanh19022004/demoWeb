<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category'])->latest();

        // Tìm kiếm theo tên hoặc SKU
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(10);
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'brand' => 'required|max:255',
            'specifications' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        // Xử lý SKU
        $validated['sku'] = 'PRD-' . Str::random(8);

        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'brand' => 'required|max:255',
            'specifications' => 'required|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        // Xử lý hình ảnh mới
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }

            // Lưu ảnh mới
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy(Product $product)
    {
        // Xóa các hình ảnh
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công.');
    }

    public function deleteImage(Request $request, Product $product)
    {
        $imagePath = $request->image;
        
        // Kiểm tra xem ảnh có tồn tại trong mảng images của sản phẩm không
        if (in_array($imagePath, $product->images)) {
            // Xóa file
            Storage::disk('public')->delete($imagePath);
            
            // Cập nhật mảng images của sản phẩm
            $images = array_values(array_diff($product->images, [$imagePath]));
            $product->update(['images' => $images]);

            return back()->with('success', 'Đã xóa hình ảnh thành công');
        }

        return back()->with('error', 'Không tìm thấy hình ảnh');
    }
} 