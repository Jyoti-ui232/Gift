@extends('layouts.admin')
@section('title', 'Customer '.$user->name)
@section('page-title', 'Customer Details')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back to Customers
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="admin-table-card mb-3">
            <div class="table-header">
                <h5><i class="fa-solid fa-user me-2" style="color:#7d8531;"></i> Profile</h5>
            </div>
            <div class="p-3 small">
                <div class="mb-2"><strong>Name:</strong> {{ $user->name }}</div>
                <div class="mb-2"><strong>Email:</strong> {{ $user->email }}</div>
                <div class="mb-2"><strong>Phone:</strong> {{ $user->phone ?: '—' }}</div>
                <div class="mb-2"><strong>Registered:</strong> {{ optional($user->created_at)->format('d M Y, h:i A') }}</div>
                <div><strong>Orders:</strong> {{ $user->orders->count() }}</div>
            </div>
        </div>

        <div class="admin-table-card">
            <div class="table-header">
                <h5><i class="fa-solid fa-location-dot me-2" style="color:#7d8531;"></i> Addresses</h5>
            </div>
            <div class="p-3 small">
                @forelse($user->addresses as $address)
                    <div class="{{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                        <strong>{{ $address->name }}</strong>
                        @if($address->is_default)
                            <span class="badge-status badge-active ms-1">Default</span>
                        @endif
                        <div class="text-muted mt-1">{{ $address->phone }}</div>
                        <div class="mt-1">{{ $address->full_address }}</div>
                    </div>
                @empty
                    <div class="text-muted">No saved addresses.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="admin-table-card">
            <div class="table-header">
                <h5><i class="fa-solid fa-bag-shopping me-2" style="color:#7d8531;"></i> Orders</h5>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders as $order)
                            <tr>
                                <td><strong>#{{ $order->order_number }}</strong></td>
                                <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                                <td>{{ $order->items->count() }}</td>
                                <td>₹{{ number_format($order->total) }}</td>
                                <td>
                                    <span class="badge-status badge-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn btn-sm btn-outline-primary" style="font-size:11px;">
                                        View
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
</div>
@endsection
