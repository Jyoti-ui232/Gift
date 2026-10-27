@extends('layouts.app')

@section('title', 'My Orders')

@include('account.partials.styles')

@section('content')
<div class="gb-account-page">
    <div class="gb-account-container">
        <div class="gb-account-header">
            <h1>My Orders</h1>
            <p>Track and review your GiftBloom orders.</p>
        </div>

        @if(session('success'))
            <div class="gb-product-alert gb-product-alert-success">{{ session('success') }}</div>
        @endif

        <div class="gb-account-layout">
            @include('account.partials.nav', ['active' => 'orders'])

            <section class="gb-account-card">
                <h2>Order history</h2>
                <p class="gb-account-help">Your recent orders appear below.</p>

                @if($orders->isEmpty())
                    <div class="gb-account-empty">
                        <i class="fa-solid fa-box" aria-hidden="true"></i>
                        <p>You haven’t placed any orders yet.</p>
                        <a href="{{ route('products.index') }}" class="gb-account-browse-btn">Browse Gifts</a>
                    </div>
                @else
                    <div class="gb-orders-list">
                        @foreach($orders as $order)
                            <div>
                                <a href="{{ route('account.orders.detail', $order) }}" class="gb-order-row">
                                    <div>
                                        <strong>#{{ $order->order_number }}</strong>
                                        <div class="gb-order-meta">
                                            {{ optional($order->created_at)->format('d M Y') }}
                                            · {{ ucfirst($order->status) }}
                                            · {{ $order->items->count() }}
                                            {{ $order->items->count() === 1 ? 'item' : 'items' }}
                                        </div>
                                    </div>
                                    <div class="gb-order-total">
                                        ₹{{ number_format($order->total) }}
                                    </div>
                                </a>
                                @if($order->status === 'pending')
                                    <form action="{{ route('account.orders.cancel', $order) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        @csrf
                                        <button type="submit" class="gb-account-browse-btn">Cancel Order</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top:18px;">
                        {{ $orders->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
