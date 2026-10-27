@php
    $active = $active ?? '';
@endphp

<aside class="gb-account-nav">
    <a href="{{ route('account.profile') }}" class="{{ $active === 'profile' ? 'is-active' : '' }}">
        <i class="fa-regular fa-user"></i> Profile
    </a>
    <a href="{{ route('account.orders') }}" class="{{ $active === 'orders' ? 'is-active' : '' }}">
        <i class="fa-solid fa-box"></i> My Orders
    </a>
    <a href="{{ route('account.wishlist') }}" class="{{ $active === 'wishlist' ? 'is-active' : '' }}">
        <i class="fa-regular fa-heart"></i> Wishlist
    </a>
    <a href="{{ route('account.addresses') }}" class="{{ $active === 'addresses' ? 'is-active' : '' }}">
        <i class="fa-solid fa-location-dot"></i> Addresses
    </a>
</aside>
