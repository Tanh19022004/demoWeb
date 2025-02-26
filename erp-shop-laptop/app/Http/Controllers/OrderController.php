<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class OrderController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        $categories = Category::where('is_active', true)->get();
        return view('orders.index', compact('orders', 'categories'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::where('is_active', true)->get();
        return view('orders.show', compact('order', 'categories'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            Log::info('Bắt đầu tạo đơn hàng', [
                'user_id' => Auth::id(),
                'cart' => $cart,
                'shipping_info' => $validated
            ]);

            // Kiểm tra số lượng tồn kho
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception("Sản phẩm không tồn tại");
                }
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Sản phẩm '{$product->name}' không đủ số lượng trong kho");
                }
            }

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'email' => Auth::user()->email,
                'notes' => $validated['notes'],
                'total' => $this->calculateTotal($cart),
                'status' => 'pending'
            ]);

            Log::info('Đã tạo đơn hàng', ['order_id' => $order->id]);

            // Tạo chi tiết đơn hàng và cập nhật số lượng tồn kho
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ]);

                // Cập nhật số lượng tồn kho
                $product->decrement('quantity', $item['quantity']);
            }

            Log::info('Đã tạo chi tiết đơn hàng và cập nhật tồn kho');

            // Xóa giỏ hàng
            session()->forget('cart');

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Đơn hàng của bạn đã được tạo thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi tạo đơn hàng: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'cart' => $cart,
                'shipping_info' => $validated ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', $e->getMessage() ?: 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.');
        }
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'pending') {
            abort(403);
        }

        try {
            DB::beginTransaction();

            // Hoàn lại số lượng tồn kho
            foreach ($order->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Đơn hàng đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi hủy đơn hàng: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi hủy đơn hàng. Vui lòng thử lại.');
        }
    }

    private function calculateTotal($cart)
    {
        return collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
    }
} 