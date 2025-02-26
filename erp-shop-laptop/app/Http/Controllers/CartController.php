<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $categories = Category::where('is_active', true)->get();
        return view('cart', compact('cart', 'categories'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Kiểm tra số lượng tồn kho
        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Số lượng sản phẩm trong kho không đủ');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            // Kiểm tra tổng số lượng sau khi thêm
            $newQuantity = $cart[$product->id]['quantity'] + $request->quantity;
            if ($product->quantity < $newQuantity) {
                return back()->with('error', 'Số lượng sản phẩm trong kho không đủ');
            }
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price' => $product->sale_price ?? $product->price,
                'image' => $product->images[0] ?? null
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json([
            'message' => 'Giỏ hàng đã được cập nhật',
            'cart_total' => $this->getCartTotal($cart)
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng',
            'cart_count' => count($cart)
        ]);
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')
            ->with('success', 'Giỏ hàng đã được xóa.');
    }

    private function getCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return number_format($total, 0, ',', '.');
    }
} 