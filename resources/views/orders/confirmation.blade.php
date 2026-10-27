@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="gb-checkout-page">
    <div class="gb-checkout-container">
        <div class="gb-confirm-card">
            <div class="gb-confirm-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h1>Order Placed Successfully</h1>
            <p>Thank you for shopping with GiftBloom. Your order has been received and will be processed soon.</p>

            <div class="gb-confirm-meta">
                <div>
                    <span>Order Number</span>
                    <strong>{{ $order->order_number }}</strong>
                </div>
                <div>
                    <span>Payment</span>
                    <strong>Cash on Delivery</strong>
                </div>
                <div>
                    <span>Total Paid Later</span>
                    <strong>₹{{ number_format($order->total) }}</strong>
                </div>
                <div>
                    <span>Delivery Date</span>
                    <strong>{{ optional($order->delivery_date)->format('d M Y') ?? '—' }}</strong>
                </div>
            </div>

            <div class="gb-confirm-address">
                <h2>Delivering To</h2>
                <p>
                    <strong>{{ $order->recipient_name }}</strong><br>
                    {{ $order->recipient_address }}<br>
                    {{ $order->recipient_city }}, {{ $order->recipient_state }} — {{ $order->recipient_pincode }}<br>
                    Phone: {{ $order->recipient_phone }}
                </p>
            </div>

            <div class="gb-confirm-items">
                <h2>Items</h2>
                @foreach($order->items as $item)
                    <div class="gb-summary-item">
                        <div>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->variant_name)
                                <span>{{ $item->variant_name }}</span>
                            @endif
                            <span>Qty: {{ $item->quantity }}</span>
                        </div>
                        <div class="gb-summary-item-total">₹{{ number_format($item->total) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="gb-confirm-actions">
                <a href="{{ route('account.orders') }}" class="gb-checkout-place-btn">View My Orders</a>
                <a href="{{ route('products.index') }}" class="gb-checkout-back">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
