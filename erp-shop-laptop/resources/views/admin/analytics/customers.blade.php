@extends('admin.layouts.app')

@section('title', 'Thống kê khách hàng')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thống kê khách hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Khách hàng</th>
                            <th>Email</th>
                            <th>Số đơn hàng</th>
                            <th>Tổng chi tiêu</th>
                            <th>Đơn hàng gần nhất</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.show', $customer) }}">
                                    {{ $customer->name }}
                                </a>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->total_orders }}</td>
                            <td>{{ number_format($customer->total_spent, 0, ',', '.') }}đ</td>
                            <td>{{ optional($customer->latest_order)->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 