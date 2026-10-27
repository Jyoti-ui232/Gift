@extends('layouts.app')

@section('title', 'My Wishlist')

@include('account.partials.styles')

@section('content')
<div class="gb-account-page">
    <div class="gb-account-container">
        <div class="gb-account-header">
            <h1>My Wishlist</h1>
            <p>Gifts you’ve saved for later.</p>
        </div>

        @if(session('success'))
            <div class="gb-product-alert gb-product-alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="gb-product-alert gb-product-alert-error">{{ session('error') }}</div>
        @endif

        <div class="gb-account-layout">
            @include('account.partials.nav', ['active' => 'wishlist'])

            <section class="gb-account-card">
                <h2>Saved gifts</h2>
                <p class="gb-account-help">
                    {{ $wishlistItems->count() }}
                    {{ $wishlistItems->count() === 1 ? 'item' : 'items' }}
                    in your wishlist.
                </p>

                @php
                    $items = $wishlistItems->filter(fn ($item) => $item->product);
                @endphp

                @if($items->isEmpty())
                    <div class="gb-account-empty">
                        <i class="fa-regular fa-heart" aria-hidden="true"></i>
                        <p>Your wishlist is empty. Start adding gifts you love!</p>
                        <a href="{{ route('products.index') }}" class="gb-account-browse-btn">Browse Gifts</a>
                    </div>
                @else
                    <div class="gb-wishlist-list">
                        @foreach($items as $item)
                            @php
                                $product = $item->product;
                                $image = $product->images->firstWhere('is_primary', true)
                                    ?? $product->images->first();
                                $imageSrc = $image?->url ?? $product->thumbnail_url;
                                $price = $product->current_price;
                                $hasSale = $product->sale_price && $product->sale_price < $product->price;
                            @endphp

                            <div class="gb-wishlist-item">
                                <a href="{{ route('products.show', $product) }}" class="gb-wishlist-image">
                                    <img src="{{ $imageSrc }}" alt="{{ $product->name }}">
                                </a>

                                <div class="gb-wishlist-info">
                                    <a href="{{ route('products.show', $product) }}" class="gb-wishlist-name">
                                        {{ $product->name }}
                                    </a>
                                    <div class="gb-wishlist-price">
                                        ₹{{ number_format($price) }}
                                        @if($hasSale)
                                            <span class="old">₹{{ number_format($product->price) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="gb-wishlist-actions">
                                    <a href="{{ route('products.show', $product) }}" class="gb-wishlist-view">
                                        View Product
                                    </a>
                                    <form method="POST" action="{{ route('wishlist.toggle', $product) }}">
                                        @csrf
                                        <button type="submit" class="gb-wishlist-remove">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection
