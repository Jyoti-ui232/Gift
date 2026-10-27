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
    $isWishlisted = in_array($product->id, $wishlistedIds ?? [], true);
@endphp

<div class="product-card"
     data-price="{{ $currentPrice }}"
     data-category="{{ $categoryName }}"
     data-delivery="{{ str_replace('_', '-', $product->delivery_type) }}"
     data-product-url="{{ route('products.show', $product) }}">

    <a href="{{ route('products.show', $product) }}"
       class="product-card-link"
       aria-label="View {{ $product->name }}"></a>

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
