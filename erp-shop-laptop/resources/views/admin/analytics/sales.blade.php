@extends('admin.layouts.app')

@section('title', 'Thống kê doanh số')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thống kê doanh số</h6>
        </div>
        <div class="card-body">
            <!-- Bộ lọc -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date">Từ ngày</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date">Đến ngày</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">Lọc</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Biểu đồ -->
            <div style="height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>

            <!-- Bảng dữ liệu -->
            <div class="table-responsive mt-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Số đơn hàng</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                            <td>{{ $sale->total_orders }}</td>
                            <td>{{ number_format($sale->total_revenue, 0, ',', '.') }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const sales = @json($sales);
    
    const labels = sales.map(sale => {
        return new Date(sale.date).toLocaleDateString('vi-VN');
    });
    
    const data = sales.map(sale => sale.total_revenue);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu',
                data: data,
                backgroundColor: '#4e73df',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(context.parsed.y);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush 