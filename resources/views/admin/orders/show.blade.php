@extends('layouts.admin')
@section('title', 'Order '.$order->order_number)
@section('page-title', 'Order #'.$order->order_number)

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back to Orders
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-8">
        <div class="admin-table-card mb-3">
            <div class="table-header">
                <h5><i class="fa-solid fa-box me-2" style="color:#7d8531;"></i> Order Items</h5>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->variant_name ?: '—' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                <div class="d-flex justify-content-between"><span>Subtotal</span><strong>₹{{ number_format($order->subtotal) }}</strong></div>
                <div class="d-flex justify-content-between"><span>Discount</span><strong>-₹{{ number_format($order->discount) }}</strong></div>
                <div class="d-flex justify-content-between"><span>Delivery</span><strong>₹{{ number_format($order->delivery_charge) }}</strong></div>
                <div class="d-flex justify-content-between mt-2"><span>Total</span><strong style="color:#7d8531;">₹{{ number_format($order->total) }}</strong></div>
            </div>
        </div>

        <div class="admin-table-card">
            <div class="table-header">
                <h5><i class="fa-solid fa-truck me-2" style="color:#7d8531;"></i> Shipment Tracking</h5>
            </div>
            <div class="p-3">
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="courier_name" class="form-label">Courier Name</label>
                        <input type="text"
                               id="courier_name"
                               name="courier_name"
                               value="{{ old('courier_name', $order->courier_name) }}"
                               class="form-control @error('courier_name') is-invalid @enderror"
                               placeholder="e.g. BlueDart, Delhivery"
                               maxlength="100">
                        @error('courier_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text"
                               id="tracking_number"
                               name="tracking_number"
                               value="{{ old('tracking_number', $order->tracking_number) }}"
                               class="form-control @error('tracking_number') is-invalid @enderror"
                               placeholder="AWB / tracking ID"
                               maxlength="100">
                        @error('tracking_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tracking_url" class="form-label">Tracking URL</label>
                        <input type="text"
                               id="tracking_url"
                               name="tracking_url"
                               value="{{ old('tracking_url', $order->tracking_url) }}"
                               class="form-control @error('tracking_url') is-invalid @enderror"
                               placeholder="https://www.courier.com/track/..."
                               maxlength="500"
                               inputmode="url">
                        @error('tracking_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Include https:// or it will be added automatically.</div>
                    </div>

                    <button type="submit" class="btn-brand">
                        <i class="fa-solid fa-floppy-disk"></i> Save Tracking
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-table-card mb-3">
            <div class="table-header">
                <h5><i class="fa-solid fa-circle-info me-2" style="color:#7d8531;"></i> Status</h5>
            </div>
            <div class="p-3">
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <label for="status" class="form-label">Order Status</label>
                    <select id="status" name="status" class="form-select mb-3">
                        @foreach(['pending','confirmed','processing','shipped','delivered'] as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                        @if(in_array($order->status, ['pending', 'confirmed', 'processing'], true))
                            <option value="cancelled">Cancelled</option>
                        @endif
                    </select>
                    <button type="submit" class="btn btn-sm btn-secondary w-100">Update Status</button>
                </form>
                <hr>
                <div class="small text-muted">
                    <div><strong>Payment:</strong> {{ ucfirst($order->payment_method ?? '—') }} / {{ ucfirst($order->payment_status) }}</div>
                    <div class="mt-1"><strong>Placed:</strong> {{ optional($order->created_at)->format('d M Y, h:i A') }}</div>
                </div>
            </div>
        </div>

        <div class="admin-table-card">
            <div class="table-header">
                <h5><i class="fa-solid fa-location-dot me-2" style="color:#7d8531;"></i> Delivery</h5>
            </div>
            <div class="p-3 small">
                <strong>{{ $order->recipient_name }}</strong><br>
                {{ $order->recipient_address }}<br>
                {{ $order->recipient_city }}, {{ $order->recipient_state }} — {{ $order->recipient_pincode }}<br>
                Phone: {{ $order->recipient_phone }}
                @if($order->delivery_date)
                    <div class="mt-2"><strong>Date:</strong> {{ $order->delivery_date->format('d M Y') }}</div>
                @endif
                @if($order->delivery_slot)
                    <div><strong>Slot:</strong> {{ $order->delivery_slot }}</div>
                @endif
                @if(filled($order->gift_message))
                    <div class="mt-2">
                        <strong>Gift message:</strong><br>
                        {{ $order->gift_message }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
