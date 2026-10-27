@extends('layouts.app')

@section('title', $q ? 'Search: '.$q : 'Search')

@section('content')
@php
    $wishlistedIds = auth()->check()
        ? auth()->user()->wishlist()->pluck('product_id')->all()
        : [];
    $hasQuery = strlen(trim((string) $q)) >= 2;
    $isPaged = $products instanceof \Illuminate\Contracts\Pagination\Paginator;
@endphp

<div class="product-listing">
    <div class="product-layout" style="display:block;">
        <div class="product-results" style="width:100%;">
            <h2 style="margin-bottom:8px;">
                @if($hasQuery)
                    Search results for “{{ $q }}”
                @else
                    Search gifts
                @endif
            </h2>
            <p style="margin:0 0 20px;color:#666;font-size:14px;">
                @if($hasQuery)
                    {{ $isPaged ? $products->total() : $products->count() }}
                    {{ ($isPaged ? $products->total() : $products->count()) === 1 ? 'product' : 'products' }} found
                @else
                    Type at least 2 characters to search GiftBloom products.
                @endif
            </p>

            @if($hasQuery && $products->count())
                <div class="product-grid">
                    @foreach($products as $product)
                        @include('products.partials.card', [
                            'product' => $product,
                            'wishlistedIds' => $wishlistedIds,
                        ])
                    @endforeach
                </div>

                @if($isPaged && $products->hasPages())
                    <div style="margin-top:28px;display:flex;justify-content:center;">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @endif
            @elseif($hasQuery)
                <div style="text-align:center;padding:48px 16px;color:#777;">
                    <p style="margin:0 0 16px;">No products matched “{{ $q }}”.</p>
                    <a href="{{ route('products.index') }}" class="gb-add-cart-btn" style="display:inline-flex;text-decoration:none;padding:0 22px;">Browse Gifts</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
