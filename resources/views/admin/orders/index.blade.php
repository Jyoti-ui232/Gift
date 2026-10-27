@extends('layouts.admin')
@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order # / customer…"
               class="form-control form-control-sm" style="width:220px;">
        <select name="status" class="form-select form-select-sm" style="width:150px;">
            <option value="">All Status</option>
            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm" style="width:150px;">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm" style="width:150px;">
        <button type="submit" class="btn btn-sm btn-secondary">
            <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>
        @if(request()->hasAny(['search','status','date_from','date_to']))
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        @endif
    </form>
</div>

<div class="admin-table-card">
    <div class="table-header">
        <h5>
            <i class="fa-solid fa-bag-shopping me-2" style="color:#7d8531;"></i>
            Orders <span class="badge bg-secondary ms-1">{{ $orders->total() }}</span>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tracking</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->order_number }}</strong></td>
                        <td>
                            {{ $order->user->name ?? $order->recipient_name }}
                            <br><small class="text-muted">{{ $order->recipient_phone }}</small>
                        </td>
                        <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                        <td>₹{{ number_format($order->total) }}</td>
                        <td>
                            <span class="badge-status badge-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            @if($order->tracking_number)
                                <small>{{ $order->courier_name ?: 'Courier' }}</small><br>
                                <code style="font-size:11px;">{{ $order->tracking_number }}</code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="p-3">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
