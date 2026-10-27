@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="gb-cart-page">
    <div class="gb-cart-container">
        <h1>Your Cart</h1>

        @if(session('success'))
            <div class="gb-product-alert gb-product-alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="gb-product-alert gb-product-alert-error">{{ session('error') }}</div>
        @endif

        @if($cartItems->count())
            <div class="gb-cart-layout">
                <div class="gb-cart-items">
                    @foreach($cartItems as $item)
                        @php
                            $product = $item->product;
                            $image = $product?->images?->firstWhere('is_primary', true)
                                ?? $product?->images?->first();
                            $imageSrc = $image?->url ?? ($product?->thumbnail_url ?? asset('assets/image/roses.jpg'));
                            $unitPrice = $item->unit_price;
                        @endphp
                        <div class="gb-cart-item">
                            <a href="{{ route('products.show', $product) }}" class="gb-cart-item-image">
                                <img src="{{ $imageSrc }}" alt="{{ $product->name }}">
                            </a>
                            <div class="gb-cart-item-info">
                                <a href="{{ route('products.show', $product) }}" class="gb-cart-item-name">
                                    {{ $product->name }}
                                </a>
                                @if($item->variant)
                                    <div class="gb-cart-item-meta">Variant: {{ $item->variant->name }}</div>
                                @endif
                                @if($item->gift_message)
                                    <div class="gb-cart-item-meta">Gift message: {{ $item->gift_message }}</div>
                                @endif
                                <div class="gb-cart-item-meta">₹{{ number_format($unitPrice) }} each</div>

                                <div class="gb-cart-item-actions">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="gb-cart-qty-form">
                                        @csrf
                                        @method('PATCH')
                                        <label>
                                            Qty
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10">
                                        </label>
                                        <button type="submit">Update</button>
                                    </form>

                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="gb-cart-remove">Remove</button>
                                    </form>
                                </div>
                            </div>
                            <div class="gb-cart-item-total">
                                ₹{{ number_format($item->total) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="gb-cart-summary">
                    <h2>Order Summary</h2>
                    <div class="gb-cart-row">
                        <span>Subtotal</span>
                        <strong>₹{{ number_format($subtotal) }}</strong>
                    </div>
                    @if($discount > 0)
                        <div class="gb-cart-row">
                            <span>Discount {{ $coupon?->code ? '('.$coupon->code.')' : '' }}</span>
                            <strong>-₹{{ number_format($discount) }}</strong>
                        </div>
                    @endif
                    <div class="gb-cart-row gb-cart-total">
                        <span>Total</span>
                        <strong>₹{{ number_format($total) }}</strong>
                    </div>

                    <form action="{{ route('cart.coupon') }}" method="POST" class="gb-cart-coupon">
                        @csrf
                        <input type="text" name="code" placeholder="Coupon code" value="{{ old('code') }}">
                        <button type="submit">Apply</button>
                    </form>

                    @if(session('coupon_code'))
                        <form action="{{ route('cart.coupon.remove') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="gb-cart-remove">Remove Coupon</button>
                        </form>
                    @endif

                    <a href="{{ route('checkout.index') }}" class="gb-cart-checkout-btn">Proceed to Checkout</a>
                    <a href="{{ route('products.index') }}" class="gb-cart-continue">Continue Shopping</a>
                </aside>
            </div>
        @else
            <div class="gb-cart-empty">
                <p>Your cart is empty.</p>
                <a href="{{ route('products.index') }}" class="gb-add-cart-btn">Browse Gifts</a>
            </div>
        @endif
    </div>
</div>
@endsection
