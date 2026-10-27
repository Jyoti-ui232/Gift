@extends('layouts.app')

@section('title', $product->name)

@php
    $galleryImages = $product->images->values();
    $primaryImage = $galleryImages->firstWhere('is_primary', true) ?? $galleryImages->first();
    $mainImageSrc = $primaryImage
        ? $primaryImage->url
        : $product->thumbnail_url;

    $currentPrice = $product->current_price;
    $hasDiscount = $currentPrice < (float) $product->price;
    $discountPercent = $hasDiscount
        ? round((($product->price - $product->sale_price) / $product->price) * 100)
        : 0;

    $approvedReviews = $product->reviews;
    $avgRating = $approvedReviews->avg('rating') ?? ($product->rating ?? 0);
    $reviewCount = $approvedReviews->count() ?: (int) ($product->reviews_count ?? 0);
    $inStock = (int) $product->stock > 0;
@endphp

@section('content')
<div class="gb-product-page">

    <div class="gb-product-breadcrumb">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}">All Gifts</a>
                </li>
                @if($product->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="gb-product-container">
        @if(session('success'))
            <div class="gb-product-alert gb-product-alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="gb-product-alert gb-product-alert-error">{{ session('error') }}</div>
        @endif

        <div class="gb-product-main">
            {{-- Gallery --}}
            <div class="gb-product-gallery">
                <div class="gb-product-main-image">
                    <img id="mainProductImage" src="{{ $mainImageSrc }}" alt="{{ $product->name }}">
                    @if($hasDiscount)
                        <span class="gb-product-discount-badge">{{ $discountPercent }}% OFF</span>
                    @endif
                </div>

                @if($galleryImages->count() > 1)
                    <div class="gb-product-thumbs" id="productThumbs">
                        @foreach($galleryImages as $img)
                            <button type="button"
                                    class="gb-product-thumb {{ ($primaryImage && $img->id === $primaryImage->id) || (!$primaryImage && $loop->first) ? 'is-active' : '' }}"
                                    data-src="{{ $img->url }}"
                                    aria-label="View image {{ $loop->iteration }}">
                                <img src="{{ $img->url }}" alt="{{ $product->name }}">
                            </button>
                        @endforeach
                    </div>
                @elseif($product->thumbnail)
                    <div class="gb-product-thumbs">
                        <button type="button" class="gb-product-thumb is-active" data-src="{{ $product->thumbnail_url }}">
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
                        </button>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="gb-product-info">
                <div class="gb-product-badges">
                    @if($product->is_bestseller)
                        <span class="gb-badge gb-badge-bestseller">Bestseller</span>
                    @endif
                    @if($product->is_new_arrival)
                        <span class="gb-badge gb-badge-new">New</span>
                    @endif
                    @if($product->is_featured)
                        <span class="gb-badge gb-badge-featured">Featured</span>
                    @endif
                    @if($product->delivery_type === 'same_day')
                        <span class="gb-badge gb-badge-delivery">Same Day Delivery</span>
                    @endif
                </div>

                <h1 class="gb-product-title">{{ $product->name }}</h1>

                @if($product->short_description)
                    <p class="gb-product-short">{{ $product->short_description }}</p>
                @endif

                <div class="gb-product-rating">
                    <div class="gb-product-stars" aria-label="Rating {{ number_format($avgRating, 1) }} out of 5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-{{ $i <= round($avgRating) ? 'solid' : 'regular' }} fa-star"></i>
                        @endfor
                    </div>
                    <span>{{ number_format((float) $avgRating, 1) }} · {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</span>
                </div>

                <div class="gb-product-price-block">
                    <span class="gb-product-current-price">₹{{ number_format($currentPrice) }}</span>
                    @if($hasDiscount)
                        <span class="gb-product-old-price">₹{{ number_format($product->price) }}</span>
                        <span class="gb-product-off">{{ $discountPercent }}% OFF</span>
                    @endif
                </div>

                <div class="gb-product-meta">
                    @if($product->sku)
                        <div><span>SKU</span><strong>{{ $product->sku }}</strong></div>
                    @endif
                    @if($product->category)
                        <div><span>Category</span><strong>{{ $product->category->name }}</strong></div>
                    @endif
                    <div>
                        <span>Availability</span>
                        @if($inStock)
                            <strong class="gb-stock-in">In Stock ({{ $product->stock }})</strong>
                        @else
                            <strong class="gb-stock-out">Out of Stock</strong>
                        @endif
                    </div>
                </div>

                @if($product->occasions->count())
                    <div class="gb-product-occasions">
                        @foreach($product->occasions as $occasion)
                            <span>{{ $occasion->name }}</span>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('cart.add') }}" method="POST" class="gb-product-cart-form" id="addToCartForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if($product->variants->count())
                        <div class="gb-product-field">
                            <label class="gb-product-label">Select Variant</label>
                            <div class="gb-variant-list">
                                @foreach($product->variants as $variant)
                                    <label class="gb-variant-option">
                                        <input type="radio"
                                               name="variant_id"
                                               value="{{ $variant->id }}"
                                               class="variant-radio"
                                               {{ ($variant->is_default || $loop->first) ? 'checked' : '' }}
                                               @disabled(!$inStock)>
                                        <span class="gb-variant-chip">
                                            {{ $variant->name }}
                                            · ₹{{ number_format($variant->current_price) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($addOns->count())
                        <div class="gb-product-field">
                            <label class="gb-product-label">
                                <i class="fa-solid fa-gift"></i> Add-Ons (Optional)
                            </label>
                            <div class="gb-addon-grid">
                                @foreach($addOns as $addon)
                                    <label class="gb-addon-option">
                                        <input type="checkbox"
                                               name="add_ons[]"
                                               value="{{ $addon->id }}"
                                               class="addon-check"
                                               @disabled(!$inStock)>
                                        <div class="gb-addon-card">
                                            @if($addon->image)
                                                <img src="{{ $addon->image_url }}" alt="{{ $addon->name }}">
                                            @endif
                                            <div class="gb-addon-name">{{ $addon->name }}</div>
                                            <div class="gb-addon-price">+₹{{ number_format($addon->price) }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="gb-product-field">
                        <label class="gb-product-label" for="giftMessage">
                            <i class="fa-regular fa-envelope"></i> Gift Message (Optional)
                        </label>
                        <textarea id="giftMessage"
                                  name="gift_message"
                                  rows="2"
                                  class="gb-product-textarea"
                                  placeholder="Write a personal message to include with the gift..."
                                  @disabled(!$inStock)></textarea>
                    </div>

                    <div class="gb-product-actions">
                        <div class="gb-qty-control">
                            <button type="button" class="gb-qty-btn" data-qty="-1" aria-label="Decrease quantity" @disabled(!$inStock)>−</button>
                            <input type="number"
                                   name="quantity"
                                   id="qtyInput"
                                   value="1"
                                   min="1"
                                   max="{{ max(1, min(10, (int) $product->stock ?: 1)) }}"
                                   @disabled(!$inStock)>
                            <button type="button" class="gb-qty-btn" data-qty="1" aria-label="Increase quantity" @disabled(!$inStock)>+</button>
                        </div>

                        <button type="submit" class="gb-add-cart-btn" @disabled(!$inStock)>
                            <i class="fa-solid fa-cart-plus"></i>
                            {{ $inStock ? 'Add to Cart' : 'Out of Stock' }}
                        </button>
                    </div>
                </form>

                <div class="gb-wishlist-wrap">
                    @auth
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="gb-wishlist-toggle {{ $isWishlisted ? 'is-active' : '' }}">
                                <i class="fa-{{ $isWishlisted ? 'solid' : 'regular' }} fa-heart"></i>
                                {{ $isWishlisted ? 'Wishlisted' : 'Add to Wishlist' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="gb-wishlist-toggle">
                            <i class="fa-regular fa-heart"></i>
                            Sign in to Wishlist
                        </a>
                    @endauth
                </div>

                <div class="gb-product-trust">
                    <div><i class="fa-solid fa-truck"></i> Free Delivery above ₹999</div>
                    <div><i class="fa-solid fa-rotate-left"></i> Easy Returns</div>
                    <div><i class="fa-solid fa-shield-halved"></i> Secure Checkout</div>
                </div>
            </div>
        </div>

        @php
            $reviewFeedback = session('success') === 'Review submitted! It will appear after approval.'
                || session('error') === 'You have already reviewed this product.'
                || collect(['product_id', 'order_id', 'rating', 'title', 'comment'])
                    ->contains(fn ($field) => $errors->has($field));
        @endphp

        {{-- Tabs --}}
        <div class="gb-product-tabs">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $reviewFeedback ? '' : 'active' }}" data-bs-toggle="tab" data-bs-target="#descTab" type="button" role="tab">Description</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $reviewFeedback ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#reviewsTab" type="button" role="tab">
                        Reviews ({{ $approvedReviews->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade {{ $reviewFeedback ? '' : 'show active' }}" id="descTab" role="tabpanel">
                    <div class="gb-product-description">
                        @if($product->description)
                            {!! nl2br(e($product->description)) !!}
                        @else
                            <p>No description available for this product yet.</p>
                        @endif
                    </div>
                </div>

                <div class="tab-pane fade {{ $reviewFeedback ? 'show active' : '' }}" id="reviewsTab" role="tabpanel">
                    @forelse($approvedReviews as $review)
                        <div class="gb-review-item">
                            <div class="gb-review-head">
                                <div class="gb-review-avatar">
                                    {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="gb-review-name">{{ $review->user->name ?? 'Anonymous' }}</div>
                                    @if($review->rating !== null)
                                        <div class="gb-product-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                                <div class="gb-review-date">{{ optional($review->created_at)->format('d M Y') }}</div>
                            </div>
                            @if($review->title)
                                <div class="gb-review-title">{{ $review->title }}</div>
                            @endif
                            <p class="gb-review-comment">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="gb-no-reviews">No reviews yet. Be the first to review this product!</p>
                    @endforelse

                    @auth
                        <div class="gb-write-review">
                            <h5>Write a Review</h5>
                            @foreach(['product_id', 'order_id', 'rating', 'title', 'comment'] as $reviewField)
                                @error($reviewField)
                                    <div class="gb-product-alert gb-product-alert-error">{{ $message }}</div>
                                @enderror
                            @endforeach
                            @if($reviewOrders->isNotEmpty())
                                <form action="{{ route('reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="gb-product-field">
                                        <label class="gb-product-label" for="review-order">Order</label>
                                        <select id="review-order" name="order_id" class="gb-product-input" required>
                                            @foreach($reviewOrders as $reviewOrder)
                                                <option value="{{ $reviewOrder->id }}">Order {{ $reviewOrder->order_number }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="gb-product-field">
                                        <span class="gb-product-label">Rating</span>
                                        <div class="gb-rating-input" role="radiogroup" aria-label="Review rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label>
                                                    <input type="radio" name="rating" value="{{ $i }}" required @checked((int) old('rating') === $i)>
                                                    <i class="{{ (int) old('rating') >= $i ? 'fa-solid' : 'fa-regular' }} fa-star rating-star {{ (int) old('rating') >= $i ? 'is-selected' : '' }}" data-val="{{ $i }}" aria-hidden="true"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="gb-product-field">
                                        <input type="text" name="title" class="gb-product-input" placeholder="Review Title">
                                    </div>
                                    <div class="gb-product-field">
                                        <textarea name="comment" rows="3" class="gb-product-textarea" placeholder="Share your experience..." required></textarea>
                                    </div>
                                    <button type="submit" class="gb-add-cart-btn gb-review-submit">Submit Review</button>
                                </form>
                            @else
                                <p>Only customers who ordered this product can submit a review.</p>
                            @endif
                        </div>
                    @else
                        <div class="gb-review-login">
                            <a href="{{ route('login') }}">Sign in</a> to write a review.
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Related --}}
        @if($relatedProducts->count())
            <section class="gb-related-section">
                <h3>You May Also Like</h3>
                <div class="gb-related-grid">
                    @foreach($relatedProducts->take(4) as $rp)
                        @php
                            $rpImage = $rp->images->firstWhere('is_primary', true) ?? $rp->images->first();
                            $rpSrc = $rpImage ? $rpImage->url : $rp->thumbnail_url;
                            $rpHasDiscount = $rp->current_price < (float) $rp->price;
                        @endphp
                        <a href="{{ route('products.show', $rp) }}" class="gb-related-card">
                            <div class="gb-related-image">
                                <img src="{{ $rpSrc }}" alt="{{ $rp->name }}">
                            </div>
                            <div class="gb-related-body">
                                <div class="gb-related-name">{{ $rp->name }}</div>
                                <div class="gb-related-price">
                                    ₹{{ number_format($rp->current_price) }}
                                    @if($rpHasDiscount)
                                        <del>₹{{ number_format($rp->price) }}</del>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const mainImage = document.getElementById('mainProductImage');
    const thumbs = document.querySelectorAll('.gb-product-thumb');
    thumbs.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            if (!mainImage) return;
            mainImage.src = this.dataset.src;
            thumbs.forEach(function (t) { t.classList.remove('is-active'); });
            this.classList.add('is-active');
        });
    });

    const qtyInput = document.getElementById('qtyInput');
    document.querySelectorAll('.gb-qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!qtyInput || qtyInput.disabled) return;
            const delta = parseInt(this.dataset.qty, 10) || 0;
            const min = parseInt(qtyInput.min, 10) || 1;
            const max = parseInt(qtyInput.max, 10) || 10;
            let next = (parseInt(qtyInput.value, 10) || 1) + delta;
            if (next < min) next = min;
            if (next > max) next = max;
            qtyInput.value = next;
        });
    });

    document.querySelectorAll('.addon-check').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const card = this.closest('.gb-addon-option')?.querySelector('.gb-addon-card');
            if (!card) return;
            card.classList.toggle('is-selected', this.checked);
        });
    });

    const ratingInputs = document.querySelectorAll('.gb-rating-input input[name="rating"]');
    const ratingStars = document.querySelectorAll('.gb-rating-input .rating-star');
    function updateReviewRating(selectedRating) {
        ratingStars.forEach(function (star) {
            const isSelected = parseInt(star.dataset.val, 10) <= selectedRating;
            star.classList.toggle('is-selected', isSelected);
            star.classList.toggle('fa-solid', isSelected);
            star.classList.toggle('fa-regular', !isSelected);
        });
    }

    ratingInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            updateReviewRating(parseInt(this.value, 10));
        });
    });

})();
</script>
@endpush
