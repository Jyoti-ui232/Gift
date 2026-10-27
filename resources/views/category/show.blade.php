
@extends('layouts.app')

@section('content')
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
            <a href="{{ route('categories.show', $category->slug) }}" class="reset-filter-link">Reset All</a>
            <button type="button" id="categoryFilterClose" aria-label="Close filters">×</button>
        </div>
          </div>

          <form method="GET" action="{{ route('categories.show', $category->slug) }}">
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
            <h2>{{ $category->name }}</h2>
            <p>{{ $category->description ?? 'Find the perfect gift for every occasion.' }}</p>

            @if($subcategories->isNotEmpty())
                <div class="category-slider">
                    <button type="button" class="slider-btn"
                            onclick="this.nextElementSibling.scrollBy({left:-220,behavior:'smooth'})">‹</button>

                    <div class="category-track">
                        @foreach($subcategories as $subcategory)
                            <a class="category-item"
                               href="{{ route('categories.show', $subcategory->slug) }}">
                                @if($subcategory->image)
                                    <img src="{{ asset('storage/' . $subcategory->image) }}"
                                         alt="{{ $subcategory->name }}">
                                @else
                                    <div class="category-placeholder">GiftBloom</div>
                                @endif
                                <p>{{ $subcategory->name }}</p>
                            </a>
                        @endforeach
                    </div>

                    <button type="button" class="slider-btn"
                            onclick="this.previousElementSibling.scrollBy({left:220,behavior:'smooth'})">›</button>
                </div>
            @endif

            <div class="product-toolbar">
                <p>{{ $products->total() }} products</p>
                <form method="GET" action="{{ url()->current() }}">
                    @foreach(request()->except('sort', 'page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <select name="sort" onchange="this.form.submit()">
                        <option value="">Recommended</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                        <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                        <option value="rating" @selected(request('sort') === 'rating')>Top Rated</option>
                    </select>
                </form>
            </div>

            <div class="product-grid">
                @forelse($products as $product)
                    @php
                        $galleryImages = $product->images->pluck('url')->filter()->values();
                        $mainImage = $product->thumbnail
                            ? asset('storage/' . $product->thumbnail)
                            : ($galleryImages->first() ?? asset('assets/image/roses.jpg'));

                        $price = (float) $product->price;
                        $currentPrice = $product->current_price;
                        $hasDiscount = $currentPrice < $price;
                        $discountPercent = $hasDiscount
                            ? round((($price - $currentPrice) / $price) * 100) : 0;

                        $isWishlisted = in_array($product->id, $wishlistedIds ?? [], false);
                    @endphp

                    <div class="product-card"
                         data-price="{{ $currentPrice }}"
                         data-product-url="{{ route('products.show', $product) }}">

                        <a href="{{ route('products.show', $product) }}"
                           class="product-card-link"
                           aria-label="View {{ $product->name }}"></a>

                        <button type="button" class="wishlist-btn"
                                data-no-nav
                                data-product-name="{{ $product->name }}"
                                data-wishlist-url="{{ auth()->check() ? route('wishlist.toggle', $product) : '' }}"
                                data-login-url="{{ route('login') }}"
                                data-wishlisted="{{ $isWishlisted ? '1' : '0' }}"
                                aria-pressed="{{ $isWishlisted ? 'true' : 'false' }}"
                                aria-label="Wishlist {{ $product->name }}">{{ $isWishlisted ? '♥' : '♡' }}</button>

                        @if($hasDiscount)
                            <span class="discount-badge">{{ $discountPercent }}% OFF</span>
                        @endif

                        <div class="product-gallery">
                            <img src="{{ $mainImage }}" class="active" alt="{{ $product->name }}">
                            @foreach($galleryImages as $image)
                                @if($image !== $mainImage)
                                    <img src="{{ $image }}" alt="{{ $product->name }}">
                                @endif
                            @endforeach
                        </div>

                        <h3>{{ $product->name }}</h3>

                        <div class="product-price">
                            <span class="current-price">₹{{ number_format($currentPrice) }}</span>
                            @if($hasDiscount)
                                <span class="old-price">₹{{ number_format($price) }}</span>
                                <span class="off">{{ $discountPercent }}% OFF</span>
                            @endif
                        </div>

                        <div class="delivery-info">
                            <span class="delivery-badge">
                                <span class="truck"><i class="fa-solid fa-truck"></i></span>
                                Same Day Delivery
                            </span>
                            <span>⏰ By 9 PM</span>
                        </div>
                    </div>
                @empty
                    <p>No products available in this category yet.</p>
                @endforelse
            </div>

            <div class="category-pagination">
                {{ $products->withQueryString()->links() }}
            </div>
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
