<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
    }

    public function process(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể xử lý đơn hàng đang ở trạng thái chờ xử lý');
        }

        $order->update(['status' => 'processing']);

        return back()->with('success', 'Đơn hàng đã được chuyển sang trạng thái đang xử lý');
    }

    public function complete(Order $order)
    {
        if ($order->status !== 'processing') {
            return back()->with('error', 'Chỉ có thể hoàn tất đơn hàng đang ở trạng thái đang xử lý');
        }

        $order->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return back()->with('success', 'Đơn hàng đã được hoàn tất thành công');
    }

    public function cancel(Order $order)
    {
        if ($order->status === 'completed' || $order->status === 'cancelled') {
            return back()->with('error', 'Không thể hủy đơn hàng đã hoàn thành hoặc đã hủy');
        }

        // Hoàn lại số lượng tồn kho
        foreach ($order->items as $item) {
            $item->product->increment('quantity', $item->quantity);
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Đơn hàng đã được hủy thành công');
    }

    public function export()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->get();

        $filename = 'orders-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ];

        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, [
            'Mã đơn hàng',
            'Khách hàng',
            'Tổng tiền',
            'Trạng thái',
            'Ngày tạo'
        ]);

        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_number,
                $order->user->name,
                number_format($order->total),
                $order->status,
                $order->created_at->format('d/m/Y H:i:s')
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, $headers);
    }
} 