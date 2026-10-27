@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe;color:#7c3aed;">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['pending_orders']) }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div class="stat-value">₹{{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="stat-label">Revenue</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;color:#2563eb;">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
            <div class="stat-label">Total Products</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fce7f3;color:#db2777;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
            <div class="stat-label">Customers</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#7d8531;">
                <i class="fa-solid fa-tags"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_categories']) }}</div>
            <div class="stat-label">Categories</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="admin-table-card">
            <div class="table-header">
                <h5><i class="fa-solid fa-receipt me-2" style="color:#7d8531;"></i>Recent Orders</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn-brand" style="padding:6px 12px;font-size:12px;">
                    View All
                </a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td><strong>#{{ $order->order_number }}</strong></td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>₹{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge-status badge-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No orders yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar column -->
    <div class="col-lg-4">
        <!-- Top Products -->
        <div class="admin-table-card mb-4">
            <div class="table-header">
                <h5><i class="fa-solid fa-fire me-2" style="color:#ef4444;"></i>Top Wishlisted</h5>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Wishlists</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                        <tr>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" style="color:#2d3748;text-decoration:none;font-weight:600;">
                                    {{ Str::limit($product->name, 25) }}
                                </a>
                            </td>
                            <td><span class="badge bg-danger">{{ $product->wishlists_count }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">No data yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly Sales -->
        <div class="admin-table-card">
            <div class="table-header">
                <h5><i class="fa-solid fa-chart-line me-2" style="color:#7d8531;"></i>Monthly Sales</h5>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlySales as $sale)
                        <tr>
                            <td>{{ \Carbon\Carbon::create($sale->year, $sale->month, 1)->format('M Y') }}</td>
                            <td>{{ $sale->count }}</td>
                            <td>₹{{ number_format($sale->total, 0) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No sales data yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
