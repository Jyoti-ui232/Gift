@extends('layouts.app')

@section('title', 'Order '.$order->order_number)

@include('account.partials.styles')

@section('content')
<div class="gb-account-page">
    <div class="gb-account-container">
        <div class="gb-account-header">
            <h1>Order #{{ $order->order_number }}</h1>
            <p>Placed on {{ optional($order->created_at)->format('d M Y, h:i A') }}</p>
        </div>

        @if(session('success'))
            <div class="gb-product-alert gb-product-alert-success">{{ session('success') }}</div>
        @endif

        <div class="gb-account-layout">
            @include('account.partials.nav', ['active' => 'orders'])

            <section class="gb-account-card">
                <h2>Order details</h2>
                <p class="gb-account-help">
                    Status: <strong>{{ ucfirst($order->status) }}</strong>
                    · Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'pending')) }}
                </p>

                @if($order->tracking_number || $order->courier_name || $order->tracking_url)
                    <div class="gb-order-tracking" style="margin-bottom:18px;padding:14px 16px;border:1px solid #ecece5;border-radius:10px;background:#f8faf0;">
                        <h3 style="margin:0 0 10px;font-size:1rem;color:#303021;">
                            <i class="fa-solid fa-truck" style="color:#7d8531;"></i> Shipment Tracking
                        </h3>
                        @if($order->courier_name)
                            <div class="gb-order-meta" style="margin-bottom:6px;">
                                Courier: <strong>{{ $order->courier_name }}</strong>
                            </div>
                        @endif
                        @if($order->tracking_number)
                            <div class="gb-order-meta" style="margin-bottom:10px;">
                                Tracking No: <strong>{{ $order->tracking_number }}</strong>
                            </div>
                        @endif
                        @if($order->tracking_url)
                            <a href="{{ $order->tracking_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="gb-account-browse-btn"
                               style="min-height:38px;padding:0 16px;font-size:0.85rem;">
                                Track Shipment
                            </a>
                        @endif
                    </div>
                @endif

                <div class="gb-wishlist-list" style="margin-bottom:18px;">
                    @foreach($order->items as $item)
                        <div class="gb-wishlist-item" style="grid-template-columns: 1fr auto;">
                            <div class="gb-wishlist-info">
                                <div class="gb-wishlist-name" style="cursor:default;">
                                    {{ $item->product_name ?? $item->product?->name ?? 'Product' }}
                                </div>
                                <div class="gb-order-meta">
                                    Qty {{ $item->quantity }}
                                    @if($item->variant_name)
                                        · {{ $item->variant_name }}
                                    @endif
                                </div>
                            </div>
                            <div class="gb-order-total">
                                ₹{{ number_format($item->total ?? ($item->price * $item->quantity)) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="gb-order-meta" style="margin-bottom:8px;">
                    Delivery to: {{ $order->recipient_name }}, {{ $order->recipient_address }},
                    {{ $order->recipient_city }}, {{ $order->recipient_state }} - {{ $order->recipient_pincode }}
                </div>
                @if(filled($order->gift_message))
                    <div class="gb-order-meta" style="margin-bottom:8px;">
                        Gift message: {{ $order->gift_message }}
                    </div>
                @endif
                <div class="gb-wishlist-price" style="font-size:1.05rem;">
                    Total: ₹{{ number_format($order->total) }}
                </div>

                @if($order->status === 'pending')
                    <form action="{{ route('account.orders.cancel', $order) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        @csrf
                        <button type="submit" class="gb-account-browse-btn">Cancel Order</button>
                    </form>
                @endif

                <div style="margin-top:18px;">
                    <a href="{{ route('account.orders') }}" class="gb-account-browse-btn">Back to Orders</a>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
