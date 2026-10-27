@extends('layouts.app')

@section('title', $occasion->name)

@section('content')
@php
    $wishlistedIds = auth()->check()
        ? auth()->user()->wishlist()->pluck('product_id')->all()
        : [];
@endphp

<div style="background:linear-gradient(135deg,#7d8531 0%,#a8b041 100%);padding:36px 30px;text-align:center;color:#fff;">
    <h1 style="font-size:30px;font-weight:800;margin:0 0 6px;">{{ $occasion->name }}</h1>
    @if($occasion->description)
        <p style="font-size:14px;opacity:0.9;margin:0 auto;max-width:600px;">{{ $occasion->description }}</p>
    @endif
    <nav style="margin-top:10px;font-size:13px;opacity:0.85;">
        <a href="{{ route('home') }}" style="color:#fff;text-decoration:none;">Home</a>
        <span style="margin:0 8px;">›</span>
        <span>{{ $occasion->name }}</span>
    </nav>
</div>

<div class="product-listing category-listing">
    <div class="product-layout">
        <button type="button" class="category-filter-open" id="categoryFilterOpen">
            <i class="fa-solid fa-sliders"></i> Filters
        </button>

        <div class="category-filter-overlay" id="categoryFilterOverlay"></div>

        <aside class="product-filters category-filter-drawer" id="categoryFilterDrawer">
            <div class="filter-heading">
                <div>
                    <h3>Filters</h3>
                    <p>Find your perfect gift</p>
                </div>
                <div class="category-filter-actions">
                    <a href="{{ route('occasions.show', $occasion->slug) }}" class="reset-filter-link">Reset All</a>
                    <button type="button" id="categoryFilterClose" aria-label="Close filters">×</button>
                </div>
            </div>

            <form method="GET" action="{{ route('occasions.show', $occasion->slug) }}">
                <div class="filter-section">
                    <h4>Price Range</h4>
                    <div class="price-inputs">
                        <label>Minimum
                            <input type="number" name="min_price" min="0" value="{{ request('min_price') }}" placeholder="₹100">
                        </label>
                        <label>Maximum
                            <input type="number" name="max_price" min="0" value="{{ request('max_price') }}" placeholder="₹4200">
                        </label>
                    </div>
                    <button type="submit">Apply Filter</button>
                </div>

                <div class="filter-section">
                    <h4>Collection</h4>
                    @foreach(['featured' => 'Featured', 'bestseller' => 'Bestseller', 'new' => 'New Arrivals'] as $value => $label)
                        <label class="filter-option">
                            <input type="radio" name="filter" value="{{ $value }}" @checked(request('filter') === $value)>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                    <label class="filter-option">
                        <input type="radio" name="filter" value="" @checked(!request('filter'))>
                        <span>All Products</span>
                    </label>
                </div>
            </form>
        </aside>

        <div class="product-results">
            <div class="occasion-product-toolbar" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:18px;">
                <p style="margin:0;color:#666;font-size:14px;">
                    <strong style="color:#333;">{{ $products->total() }}</strong>
                    {{ $products->total() === 1 ? 'product' : 'products' }} found
                </p>
                <form method="GET" action="{{ route('occasions.show', $occasion) }}" style="display:flex;gap:10px;align-items:center;">
                    @foreach(request()->except('sort', 'page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <select name="sort" onchange="this.form.submit()"
                            style="padding:7px 14px;border:1px solid #ddd;border-radius:8px;font-size:13px;color:#444;background:#fff;">
                        <option value="">Sort By</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                        <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                        <option value="rating" @selected(request('sort') === 'rating')>Top Rated</option>
                    </select>
                </form>
            </div>

            @if($products->count())
                <div class="product-grid">
                    @foreach($products as $product)
                        @include('products.partials.card', [
                            'product' => $product,
                            'wishlistedIds' => $wishlistedIds,
                        ])
                    @endforeach
                </div>

                @if($products->hasPages())
                    <div style="margin-top:28px;display:flex;justify-content:center;">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div style="text-align:center;padding:48px 16px;color:#777;">
                    <p style="margin:0 0 16px;">No products available for this occasion yet.</p>
                    <a href="{{ route('products.index') }}" class="gb-add-cart-btn" style="display:inline-flex;text-decoration:none;padding:0 22px;">Browse Gifts</a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const drawer = document.getElementById('categoryFilterDrawer');
    const overlay = document.getElementById('categoryFilterOverlay');
    const open = document.getElementById('categoryFilterOpen');
    const close = document.getElementById('categoryFilterClose');

    if (!drawer || !overlay || !open || !close) return;

    function openDrawer() {
        drawer.classList.add('is-open');
        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawer.classList.remove('is-open');
        overlay.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    open.addEventListener('click', openDrawer);
    close.addEventListener('click', closeDrawer);
    overlay.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeDrawer();
    });
});
</script>
@endsection
