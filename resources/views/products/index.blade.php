@extends('layouts.app')

@section('content')
<div class="product-listing">
 <div class="product-layout">

   <div class="filter-overlay" id="filterOverlay" hidden></div>
   
   <aside class="product-filters" id="productFilters">
    <div class="filter-heading">
        <div>
            <h3>Filters</h3>
            <p>Find your perfect gift</p>
        </div>
        <div class="filter-heading-actions">
            <button type="button" id="resetFilters">Reset All</button>
            <button type="button" class="filter-close-btn" id="closeFiltersBtn" aria-label="Close filters">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <div class="filter-section">
        <h4>Price Range</h4>

        <div class="price-inputs">
            <label>
                Minimum
                <input type="number" id="minPrice" placeholder="₹100">
            </label>

            <label>
                Maximum
                <input type="number" id="maxPrice" placeholder="₹4200">
            </label>
        </div>

        <button type="button" id="applyPriceFilter">
            Apply Filter
        </button>
    </div>

    <div class="filter-section">
        <h4>Gift Type</h4>

        <label class="filter-option">
            <input type="checkbox" value="Flowers">
            <span>Flowers</span>
        </label>

        <label class="filter-option">
            <input type="checkbox" value="Cakes">
            <span>Cakes</span>
        </label>

        <label class="filter-option">
            <input type="checkbox" value="Balloons">
            <span>Balloons</span>
        </label>

        <label class="filter-option">
            <input type="checkbox" value="Personalized Gifts">
            <span>Personalized Gifts</span>
        </label>
    </div>

    <div class="filter-section">
        <h4>Delivery</h4>

        <label class="filter-option">
            <input type="checkbox" value="same-day">
            <span>Same Day Delivery</span>
        </label>
    </div>
   </aside>

  
  <div class="product-results">
    <h2>All Gift</h2>
    <p>Find the perfect gift for every occasion.</p>

    <button type="button" class="mobile-filter-btn" id="openFiltersBtn" aria-controls="productFilters">
        <i class="fa-solid fa-sliders"></i>
        Filter
    </button>
    
    <div class="category-slider">
       <button type="button" class="slider-btn"
        onclick="moveCategories(-1)">&#8249;</button>

      <div class="category-track" id="categoryTrack">
        <div class="category-item">
            <img src="{{ asset('assets/image/roses.jpg') }}" alt="Flowers">
            <p>Flowers</p>
        </div>

        <div class="category-item">
            <img src="assets/image/cakes.jpg" alt="Cakes">
            <p>Cakes</p>
        </div>

        <div class="category-item">
            <img src="assets/image/chocolates.jpg" alt="Chocolates">
            <p>Chocolates</p>
        </div>

        <div class="category-item">
            <img src="assets/image/lamp.jpg" alt="Personalised">
            <p>Personalised</p>
        </div>

        <div class="category-item">
            <img src="assets/image/plants.jpg" alt="Plants">
            <p>Plants</p>
        </div>

        <div class="category-item">
            <img src="assets/image/cake.jpg" alt="Chocolates Cakes">
            <p>Chocolates Cakes</p>
        </div>

        <div class="category-item">
            <img src="assets/image/hamper.jpg" alt="">
            <p>Gift Hampers</p>
        </div>

        <div class="category-item">
            <img src="assets/image/cakes.jpg" alt="">
            <p>Soft Toys</p>
        </div>
      </div>

       <button type="button" class="slider-btn"
        onclick="moveCategories(1)">&#8250;</button>
    </div>


    
<div class="product-grid">

    @forelse($products as $product)

        @php
            $galleryImages = $product->images
                ->pluck('url')
                ->filter()
                ->values();

            $mainImage = $product->thumbnail
                ? asset('storage/' . $product->thumbnail)
                : ($galleryImages->first() ?? asset('assets/image/roses.jpg'));

            $price = (float) $product->price;
            $currentPrice = $product->current_price;
            $hasDiscount = $currentPrice < $price;
            $discountPercent = $hasDiscount
                ? round((($price - $currentPrice) / $price) * 100)
                : 0;

            $categoryName = $product->category->name ?? '';
        @endphp

        <div class="product-card"
             data-price="{{ $currentPrice }}"
             data-category="{{ $categoryName }}"
             data-delivery="{{ str_replace('_', '-', $product->delivery_type) }}"
             data-product-url="{{ route('products.show', $product) }}">

            <a href="{{ route('products.show', $product) }}"
               class="product-card-link"
               aria-label="View {{ $product->name }}"></a>

            @php
                $isWishlisted = in_array($product->id, $wishlistedIds ?? [], true);
            @endphp
            <button type="button"
                    class="wishlist-btn"
                    data-no-nav
                    data-product-name="{{ $product->name }}"
                    data-wishlist-url="{{ auth()->check() ? route('wishlist.toggle', $product) : '' }}"
                    data-login-url="{{ route('login') }}"
                    data-wishlisted="{{ $isWishlisted ? '1' : '0' }}"
                    aria-pressed="{{ $isWishlisted ? 'true' : 'false' }}"
                    aria-label="{{ $isWishlisted ? 'Remove '.$product->name.' from wishlist' : 'Add '.$product->name.' to wishlist' }}">{{ $isWishlisted ? '♥' : '♡' }}</button>

            @if($hasDiscount)
                <span class="discount-badge">
                    {{ $discountPercent }}% OFF
                </span>
            @endif

            <div class="product-gallery">

                <img src="{{ $mainImage }}"
                     class="active"
                     alt="{{ $product->name }}">

                @foreach($galleryImages as $image)
                    @if($image !== $mainImage)
                        <img src="{{ $image }}" alt="{{ $product->name }}">
                    @endif
                @endforeach

                <div class="slider-dots" data-no-nav>
                    <span class="active"></span>

                    @foreach($galleryImages as $image)
                        @if($image !== $mainImage)
                            <span></span>
                        @endif
                    @endforeach
                </div>

            </div>

            <h3>{{ $product->name }}</h3>

            <div class="product-price">
                <span class="current-price">
                    ₹{{ number_format($currentPrice) }}
                </span>

                @if($hasDiscount)
                    <span class="old-price">
                        ₹{{ number_format($price) }}
                    </span>

                    <span class="off">
                        {{ $discountPercent }}% OFF
                    </span>
                @endif
            </div>

            <div class="delivery-info">
                <span class="delivery-badge">
                    <span class="truck">
                        <i class="fa-solid fa-truck"></i>
                    </span>
                    Same Day Delivery
                </span>

                <span>⏰ By 9 PM</span>
            </div>

        </div>

    @empty
        <p>No products available yet.</p>
    @endforelse

</div>

<div class="product-pagination">
    {{ $products->links() }}
</div>

  
  </div>
 </div>


<section class="customer-reviews">
    <div class="customer-reviews-header">
        <h2>Customer Reviews</h2>
        <button type="button" class="show-reviews-btn">
            Show All Reviews <span>›</span>
        </button>
    </div>

    <div class="customer-reviews-track" id="reviewsTrack">

        <div class="customer-review-card">
            <div class="review-user">
                <div class="review-avatar">P</div>
                <div>
                    <div class="review-user-name">Priya S. <span>• 1 day ago</span></div>
                    <div class="review-stars">★★★★★</div>
                </div>
            </div>
            <p class="review-message">Great staff handled flowers in a beautiful way... <a href="#">read more</a></p>
            <div class="review-tags">
                <span>Occasion: Birthday</span>
                <span>City: Delhi</span>
            </div>
        </div>

        <div class="customer-review-card">
            <div class="review-user">
                <div class="review-avatar">R</div>
                <div>
                    <div class="review-user-name">Rahul G. <span>• 1 day ago</span></div>
                    <div class="review-stars">★★★★★</div>
                </div>
            </div>
            <p class="review-message">Beautiful gift and excellent packaging!</p>
            <div class="review-tags">
                <span>Occasion: Birthday</span>
                <span>City: Noida</span>
            </div>
        </div>

        <div class="customer-review-card">
            <div class="review-user">
                <div class="review-avatar">S</div>
                <div>
                    <div class="review-user-name">Simran K. <span>• 2 days ago</span></div>
                    <div class="review-stars">★★★★★</div>
                </div>
            </div>
            <p class="review-message">Delivery was on time and the flowers were fresh.</p>
            <div class="review-tags">
                <span>Occasion: Anniversary</span>
                <span>City: Delhi</span>
            </div>
        </div>

        <div class="customer-review-card">
            <div class="review-user">
                <div class="review-avatar">P</div>
                <div>
                    <div class="review-user-name">Priya S. <span>• 1 day ago</span></div>
                    <div class="review-stars">★★★★★</div>
                </div>
            </div>
            <p class="review-message">Great staff handled flowers in a beautiful way... <a href="#">read more</a></p>
            <div class="review-tags">
                <span>Occasion: Birthday</span>
                <span>City: Delhi</span>
            </div>
        </div>

        <div class="customer-review-card">
            <div class="review-user">
                <div class="review-avatar">A</div>
                <div>
                    <div class="review-user-name">Ananya M. <span>• 3 days ago</span></div>
                    <div class="review-stars">★★★★★</div>
                </div>
            </div>
            <p class="review-message">Loved the personalised gift. Will order again!</p>
            <div class="review-tags">
                <span>Occasion: Birthday</span>
                <span>City: Gurugram</span>
            </div>
        </div>

    </div>

    <button type="button" class="reviews-next"
        onclick="document.getElementById('reviewsTrack').scrollBy({left:280,behavior:'smooth'})">
        ›
    </button>
</section>
</div>
@endsection

@push('styles')
<style>
    .mobile-filter-btn,
    .filter-close-btn,
    .filter-overlay {
        display: none;
    }

    .filter-heading-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    @media (max-width: 767px) {
        /* Truck is hover-only on desktop; show it on mobile/touch */
        .product-listing .product-card .truck {
            opacity: 1;
            transform: translateX(0);
        }

        .mobile-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 14px;
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            color: #333;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .mobile-filter-btn i {
            color: #7d8531;
            font-size: 14px;
        }

        .filter-close-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 1px solid #e5e5e5;
            border-radius: 50%;
            background: #fff;
            color: #555;
            cursor: pointer;
            flex-shrink: 0;
        }

        .filter-overlay {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }

        .filter-overlay.is-open {
            opacity: 1;
            visibility: visible;
        }

        .product-listing .product-layout {
            display: block;
        }

        .product-listing .product-filters {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: min(320px, 88vw);
            max-width: 100%;
            height: 100%;
            max-height: 100vh;
            max-height: 100dvh;
            margin: 0;
            padding: 18px 16px 24px;
            border-radius: 0;
            background: #fff;
            box-shadow: -8px 0 24px rgba(0, 0, 0, 0.12);
            z-index: 1050;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            transform: translateX(100%);
            transition: transform 0.28s ease;
        }

        .product-listing .product-filters.is-open {
            transform: translateX(0);
        }

        .product-listing .product-filters .filter-heading {
            position: sticky;
            top: -18px;
            z-index: 2;
            margin: -18px -16px 0;
            padding: 18px 16px 12px;
            background: #fff;
        }

        .product-listing .product-filters #applyPriceFilter {
            position: sticky;
            bottom: 0;
            z-index: 2;
            background: #7d8531;
        }

        html.filters-drawer-open,
        body.filters-drawer-open {
            overflow: hidden;
            overscroll-behavior: none;
        }

        body.filters-drawer-open .filter-overlay {
            touch-action: none;
        }
    }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var filters = document.getElementById('productFilters');
    var overlay = document.getElementById('filterOverlay');
    var openBtn = document.getElementById('openFiltersBtn');
    var closeBtn = document.getElementById('closeFiltersBtn');
    var applyBtn = document.getElementById('applyPriceFilter');
    var savedScrollY = 0;

    if (!filters || !overlay || !openBtn || !closeBtn) return;

    function isMobileFilters() {
        return window.matchMedia('(max-width: 767px)').matches;
    }

    function shouldBlockPageScroll(event) {
        return filters.classList.contains('is-open')
            && isMobileFilters()
            && !filters.contains(event.target);
    }

    function onTouchMove(event) {
        if (shouldBlockPageScroll(event)) {
            event.preventDefault();
        }
    }

    function onWheel(event) {
        if (shouldBlockPageScroll(event)) {
            event.preventDefault();
        }
    }

    function restoreScrollPosition() {
        window.scrollTo(0, savedScrollY);
    }

    function lockBodyScroll() {
        savedScrollY = window.scrollY || window.pageYOffset || 0;
        document.documentElement.classList.add('filters-drawer-open');
        document.body.classList.add('filters-drawer-open');
        document.addEventListener('touchmove', onTouchMove, { passive: false });
        document.addEventListener('wheel', onWheel, { passive: false });
        restoreScrollPosition();
    }

    function unlockBodyScroll() {
        document.documentElement.classList.remove('filters-drawer-open');
        document.body.classList.remove('filters-drawer-open');
        document.removeEventListener('touchmove', onTouchMove, { passive: false });
        document.removeEventListener('wheel', onWheel, { passive: false });
        restoreScrollPosition();
        window.setTimeout(restoreScrollPosition, 0);
        window.setTimeout(restoreScrollPosition, 50);
    }

    function openFilters() {
        if (filters.classList.contains('is-open')) return;
        filters.classList.add('is-open');
        overlay.classList.add('is-open');
        overlay.hidden = false;
        lockBodyScroll();
    }

    function closeFilters() {
        if (!filters.classList.contains('is-open')) return;
        if (document.activeElement && filters.contains(document.activeElement)) {
            document.activeElement.blur();
        }
        filters.classList.remove('is-open');
        overlay.classList.remove('is-open');
        unlockBodyScroll();
        window.setTimeout(function () {
            if (!overlay.classList.contains('is-open')) {
                overlay.hidden = true;
            }
        }, 280);
    }

    openBtn.addEventListener('click', openFilters);
    closeBtn.addEventListener('click', closeFilters);
    overlay.addEventListener('click', closeFilters);
    overlay.addEventListener('wheel', function (event) {
        if (filters.classList.contains('is-open')) {
            event.preventDefault();
        }
    }, { passive: false });

    if (applyBtn) {
        applyBtn.addEventListener('click', function () {
            if (filters.classList.contains('is-open') && isMobileFilters()) {
                closeFilters();
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && filters.classList.contains('is-open')) {
            closeFilters();
        }
    });
  });
</script>
@endpush
