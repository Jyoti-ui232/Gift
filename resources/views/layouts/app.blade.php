<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'GiftBloom')) — GiftBloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
<div id="app">

@php
    $isAuthPage = request()->routeIs('login', 'register', 'password.*');
    $cartCount = 0;
    try {
        $cartQuery = \App\Models\CartItem::query();
        if (auth()->check()) {
            $cartQuery->where('user_id', auth()->id());
        } else {
            $cartQuery->where('session_id', session()->getId());
        }
        $cartCount = $cartQuery->sum('quantity');
    } catch (\Exception $e) {
    }
@endphp

@if ($isAuthPage)
    <!-- ================= AUTH HEADER (logo + support phone only) ================= -->
    <header class="gb-auth-topbar" id="authCustomerHeader">
        <a href="{{ route('home') }}" class="logo-section gb-auth-topbar-logo" style="text-decoration:none;">
            <div class="logo-icon">
                <i class="fa-solid fa-gift"></i>
            </div>
            <div class="logo-text">GiftBloom</div>
        </a>

        <a href="tel:+919999999999" class="gb-auth-topbar-phone">
            <i class="fa-solid fa-phone" aria-hidden="true"></i>
            <span>+91 99999 99999</span>
        </a>
        <button class="header-action" id="mobileMenuBtn" type="button" aria-expanded="false" aria-label="Open menu">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>
        <a href="{{ route('search') }}" class="mobile-search-action" aria-label="Search">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
        </a>
    </header>
    <nav class="navigation auth-mobile-navigation" id="mainNavigation" aria-label="Customer navigation">
        <div class="nav-menu">
            <div class="nav-dropdown"><a href="{{ route('home') }}">Home</a></div>
            <div class="nav-dropdown"><a href="{{ route('products.index') }}">Shop Gifts</a></div>
            <div class="nav-dropdown"><a href="{{ route('cart.index') }}">Cart</a></div>
            <div class="nav-dropdown"><a href="{{ route('order.track') }}">Track Order</a></div>
        </div>
    </nav>
@else
    <!-- ================= HEADER ================= -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo-section" style="text-decoration:none;">
            <div class="logo-icon">
                <i class="fa-solid fa-gift"></i>
            </div>
            <div class="logo-text">GiftBloom</div>
        </a>

        <!-- Delivery -->
        <div class="delivery-section" id="deliveryLocation" style="cursor:pointer;">
            <div class="india-flag">🇮🇳</div>
            <div class="delivery-text">
                <span>Deliver to</span>
                <strong>
                    Delhi
                    <i class="fa-solid fa-chevron-down" style="font-size:10px;"></i>
                </strong>
                <small>New Delhi, 110086</small>
            </div>
        </div>

        <div class="location-overlay" id="locationOverlay">
            <div class="location-popup">
                <button type="button" class="location-close" id="locationClose">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="location-title">
                    <i class="fa-regular fa-location-dot"></i>
                    <div>
                        <h3>Let's Personalize Your Experience!</h3>
                        <p>Find the perfect gifts for you or your loved ones.</p>
                    </div>
                </div>
                <div class="location-field country-field">
                    <span>🇮🇳</span>
                    <strong>India</strong>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="location-field pincode-field">
                    <input type="text" id="locationPincode" maxlength="6" inputmode="numeric" value="110086" placeholder="Enter pincode">
                    <button type="button" id="clearPincode"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <button type="button" class="location-confirm" id="locationConfirm">Confirm</button>
            </div>
        </div>

        <!-- Finder -->
        <div class="search-wrap">
                <form class="header-search" action="{{ route('search') }}" method="get" role="search">
                    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                        <path d="M20 20l-3.5-3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Sending good luck plants or ..." aria-label="Search">
                </form>
                <button type="button" class="finder-btn" aria-label="Open Gift Finder">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="4" y="10" width="16" height="10" rx="1.5" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M12 7v13M4 13h16" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M9 7c0-2 1.2-3.5 3-3.5S15 5 15 7" stroke="#e91e8c" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    Finder
                </button>
            </div>


        <!-- Header Actions -->
        <div class="header-actions">

            <a href="{{ route('account.orders') }}" class="header-action" title="My Orders">
                <i class="fa-regular fa-bell"></i>
                <span>My Orders</span>
            </a>

            <div class="header-action">
                <i class="fa-solid fa-indian-rupee-sign"></i>
                <span>INR</span>
            </div>

            <div class="header-action">
                <i class="fa-solid fa-building"></i>
                <span>Corporate</span>
            </div>

            <a href="{{ route('cart.index') }}" class="header-action" title="Cart">
                <div style="position:relative;display:inline-block;">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @if($cartCount > 0)
                        <span style="position:absolute;top:-8px;right:-10px;background:#7d8531;color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;display:flex;align-items:center;justify-content:center;font-weight:700;">{{ $cartCount }}</span>
                    @endif
                </div>
                <span>Cart</span>
            </a>

            <!-- User Menu -->
            @auth
                <div class="header-action" style="position:relative;" id="userMenu">
                    <i class="fa-regular fa-user"></i>
                    <span>Hi {{ Str::limit(Auth::user()->name, 8) }}</span>
                    <div id="userDropdown" style="display:none;position:absolute;top:100%;right:0;background:#fff;border:1px solid #eee;border-radius:8px;min-width:180px;box-shadow:0 4px 12px rgba(0,0,0,0.12);z-index:9999;padding:8px 0;">
                        <a href="{{ route('account.profile') }}" style="display:block;padding:10px 16px;color:#333;text-decoration:none;font-size:13px;"><i class="fa-regular fa-user me-2"></i>My Profile</a>
                        <a href="{{ route('account.orders') }}" style="display:block;padding:10px 16px;color:#333;text-decoration:none;font-size:13px;"><i class="fa-solid fa-box me-2"></i>My Orders</a>
                        <a href="{{ route('account.wishlist') }}" style="display:block;padding:10px 16px;color:#333;text-decoration:none;font-size:13px;"><i class="fa-regular fa-heart me-2"></i>Wishlist</a>
                        <a href="{{ route('account.addresses') }}" style="display:block;padding:10px 16px;color:#333;text-decoration:none;font-size:13px;"><i class="fa-solid fa-location-dot me-2"></i>Addresses</a>
                        @if(Auth::user()->is_admin)
                            <hr style="margin:4px 0;">
                            <a href="{{ route('admin.dashboard') }}" style="display:block;padding:10px 16px;color:#7d8531;text-decoration:none;font-size:13px;font-weight:600;"><i class="fa-solid fa-shield-halved me-2"></i>Admin Panel</a>
                        @endif
                        <hr style="margin:4px 0;">
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" style="display:block;width:100%;padding:10px 16px;background:none;border:none;text-align:center;color:#e53e3e;font-size:13px;cursor:pointer;"><i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="header-action">
                    <i class="fa-regular fa-user"></i>
                    <span>Sign In</span>
                </a>
            @endauth

            <button class="header-action" id="mobileMenuBtn" type="button" aria-expanded="false" aria-label="Open menu">
                <i class="fa-solid fa-bars"></i>
                <span>More</span>
            </button>

        </div>
        <a href="{{ route('search') }}" class="mobile-search-action" aria-label="Search">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
        </a>
    </header>


    <!-- ================= TOP NAVIGATION ================= -->
    @php
        try {
            $navCategories = $navCategories ?? \App\Models\Category::where('is_active', true)->where('show_in_nav', true)->whereNull('parent_id')->with(['children' => function($q){ $q->where('is_active', true)->orderBy('sort_order'); }])->orderBy('sort_order')->limit(12)->get();
        } catch(\Exception $e) {
            $navCategories = collect();
        }
        try {
            $navOccasions = \App\Models\Occasion::where('is_active', true)->orderBy('sort_order')->limit(8)->get();
        } catch(\Exception $e) {
            $navOccasions = collect();
        }
    @endphp

    <nav class="navigation" id="mainNavigation" aria-label="Gift categories">
      <div class="nav-menu">

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'birthday-gifts') }}">Birthday</a>
        </div>

        <div class="nav-dropdown">
            <a href="#"> Occasions <i class="fa-solid fa-chevron-down"></i></a>
            <div class="dropdown-menu">
                @foreach($navOccasions as $occ)
                    <a href="{{ route('occasions.show', $occ->slug) }}">
                        {{ $occ->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'anniversary-gifts') }}">Anniversary</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'flowers') }}">Flowers</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'cakes') }}">Cakes</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'personalized-gifts') }}">Personalised</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'plants') }}">Plants</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'chocolates') }}">Chocolates</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'lifestyle') }}">Lifestyle</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'hatke') }}">Hatke</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'luxe') }}">LUXE</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'gift-hampers') }}">Hampers</a>
        </div>

        <div class="nav-dropdown">
            <a href="{{ route('categories.show', 'balloons') }}">Balloons</a>
        </div>

        @auth
            <div class="nav-dropdown mobile-account-entry">
                <a href="{{ route('account.profile') }}">My Profile</a>
            </div>
            <div class="nav-dropdown mobile-account-entry">
                <a href="{{ route('account.orders') }}">My Orders</a>
            </div>
            <div class="nav-dropdown mobile-account-entry">
                <a href="{{ route('account.wishlist') }}">Wishlist</a>
            </div>
            <div class="nav-dropdown mobile-account-entry">
                <a href="{{ route('account.addresses') }}">Addresses</a>
            </div>
            <div class="nav-dropdown mobile-account-entry">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Sign Out</button>
                </form>
            </div>
        @endauth

      </div>
    </nav>
@endif

<!-- ================= ICON CATEGORY SLIDER ================= -->
@if (
    !$isAuthPage &&
    !request()->routeIs('verification.*') &&
    !request()->routeIs('cart.*') &&
    !request()->routeIs('checkout.*') &&
    !request()->routeIs('account.*') &&
    !request()->routeIs('order.confirmation') &&
    !request()->routeIs('order.track', 'order.track.submit') &&
    !request()->routeIs('products.show') &&
    !request()->routeIs('pages.*')
)
    @php
        $iconConfig = [
            ['img' => 'assets/image/icon/diwali.svg', 'label' => 'Diwali', 'slug' => 'diwali', 'url' => route('occasions.show', ['occasion' => 'festivals'])],
            ['img' => 'assets/image/icon/day.svg', 'label' => 'Birthday', 'slug' => 'birthday-gifts'],
            ['img' => 'assets/image/icon/flowers.svg', 'label' => 'Flowers', 'slug' => 'flowers'],
            ['img' => 'assets/image/icon/cakes.svg', 'label' => 'Cakes', 'slug' => 'cakes'],
            ['img' => 'assets/image/icon/Personalised.svg', 'label' => 'Personalised', 'slug' => 'personalized-gifts'],
            ['img' => 'assets/image/icon/plants.svg', 'label' => 'Plants', 'slug' => 'plants'],
            ['img' => 'assets/image/icon/Chocolates.webp', 'label' => 'Chocolates', 'slug' => 'chocolates'],
            ['img' => 'assets/image/icon/hampers.webp', 'label' => 'Hampers', 'slug' => 'gift-hampers'],
            ['img' => 'assets/image/icon/balloon.svg', 'label' => 'Balloons', 'slug' => 'balloons'],
            ['img' => 'assets/image/icon/corporate.svg', 'label' => 'Corporate', 'slug' => 'corporate', 'url' => route('categories.show', ['category' => 'gifts'])],
            ['img' => 'assets/image/icon/hatke.svg', 'label' => 'Hatke', 'slug' => 'hatke'],
            ['img' => 'assets/image/icon/luxe.svg', 'label' => 'LUXE', 'slug' => 'luxe'],
            ['img' => 'assets/image/icon/home.svg', 'label' => 'Home', 'slug' => 'lifestyle'],
        ];

        $iconSliderCats = \App\Models\Category::where('is_active', true)
            ->whereIn('slug', array_column($iconConfig, 'slug'))
            ->get()
            ->keyBy('slug');
    @endphp

    <section class="icon-category-wrapper">
        <button class="nav-arrow nav-left" id="leftArrow" type="button">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="icon-category-menu" id="categorySlider">
            @foreach($iconConfig as $ic)
                @php
                    $matchedCat = $iconSliderCats->get($ic['slug']);
                    $catUrl = $matchedCat
                        ? route('categories.show', ['category' => $matchedCat->slug])
                        : ($ic['url'] ?? '#');
                @endphp

                <a href="{{ $catUrl }}"
                   class="icon-category"
                   style="text-decoration:none;color:inherit;">
                    <img src="{{ asset($ic['img']) }}"
                         alt="{{ $ic['label'] }}"
                         onerror="this.style.display='none'">
                    <span>{{ $ic['label'] }}</span>
                </a>
            @endforeach
        </div>

        <button class="nav-arrow nav-right" id="rightArrow" type="button">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </section>
@endif
<!-- ================= END ICON CATEGORY SLIDER ================= -->



    <!-- ================= FLASH MESSAGES ================= -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert" style="border-radius:8px;">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert" style="border-radius:8px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <!-- ================= PAGE CONTENT ================= -->
    <main>
        @yield('content')
    </main>



<!-- ================= FOOTER ================= -->
   @if (!$isAuthPage)
     <footer class="giftly-footer">

    <div class="footer-container">

        <!-- Policy Info -->
        <div class="footer-column">
            <h4>Policy Info</h4>
            <a href="{{ route('pages.terms') }}">Terms &amp; Conditions</a>
            <a href="{{ route('pages.privacy') }}">Privacy Policy</a>
            <a href="{{ route('pages.shipping') }}">Shipping &amp; Delivery</a>
            <a href="{{ route('pages.refund') }}">Cancellation &amp; Refund</a>
        </div>

        <!-- About Company -->
        <div class="footer-column">
            <h4>About Company</h4>
            <a href="{{ route('pages.about') }}">About GiftBloom</a>
            <a href="{{ route('search') }}">Explore Gifts</a>
            <a href="{{ route('products.index') }}">All Products</a>
            <a href="{{ route('occasions.show', ['occasion' => 'anniversary']) }}">Occasion Gifts</a>
        </div>

        <!-- GiftBloom Business -->
        <div class="footer-column">
            <h4>GiftBloom Business</h4>
            <a href="{{ route('search') }}">Corporate Gifts</a>
            <a href="{{ route('search') }}">Personalised Gifts</a>
            <a href="{{ route('products.index') }}">Gift Collections</a>
            <a href="{{ route('search') }}">Explore Products</a>
        </div>

        <!-- Need Help -->
        <div class="footer-column">
            <h4>Need Help?</h4>
            <a href="{{ route('order.track') }}">Track Order</a>
            <a href="{{ route('pages.contact') }}">Contact Us</a>
            <a href="{{ route('pages.faq') }}">FAQs</a>
        </div>

        <!-- Gift Categories -->
        <div class="footer-column">
            <h4>Gift Categories</h4>
            @php
                try {
                    $footerCats = \App\Models\Category::where('is_active', true)
                        ->whereNull('parent_id')
                        ->orderBy('sort_order')
                        ->limit(6)
                        ->get();
                } catch (\Exception $e) {
                    $footerCats = collect();
                }
            @endphp

            @forelse($footerCats as $fcat)
                <a href="{{ route('categories.show', $fcat->slug) }}">{{ $fcat->name }}</a>
            @empty
                <a href="{{ route('products.index') }}">Flowers</a>
                <a href="{{ route('products.index') }}">Cakes</a>
                <a href="{{ route('products.index') }}">Gifts</a>
            @endforelse
        </div>

        <!-- Subscribe -->
        <div class="footer-column footer-subscribe">
            <h4>Subscribe Now</h4>
            <p>Get updates on promotions and offers.</p>
            <form action="{{ route('home') }}" method="GET" class="footer-subscribe-form">
                <input type="email" name="email" placeholder="Enter email address" aria-label="Email address" required>
                <button type="submit" aria-label="Subscribe">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>

    </div>

    <div class="footer-company">
        <p><strong>GiftBloom</strong> | Thoughtful gifts for every special moment.</p>
        <p>For support: support@giftbloom.com</p>
    </div>

    <div class="footer-bottom">
        <div class="footer-social">
            <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" aria-label="Pinterest"><i class="fa-brands fa-pinterest-p"></i></a>
        </div>

        <p>© {{ date('Y') }} GiftBloom. All Rights Reserved.</p>

        <div class="footer-made">
            Made with <i class="fa-solid fa-heart"></i> for gifting moments
        </div>
    </div>

     </footer>
   @endif

@php
    $profileUrl = auth()->check() ? route('account.profile') : route('login');
    $searchActive = request()->routeIs('search', 'products.*', 'categories.show', 'occasions.show');
    $cartActive = request()->routeIs('cart.*', 'checkout.*');
    $profileActive = request()->routeIs('account.*', 'login', 'register', 'password.*');
@endphp
<nav class="mobile-bottom-nav" aria-label="Quick navigation">
    <a href="{{ route('home') }}"
       class="mobile-bottom-nav-item {{ request()->routeIs('home') ? 'is-active' : '' }}"
       @if(request()->routeIs('home')) aria-current="page" @endif>
        <i class="fa-solid fa-house" aria-hidden="true"></i>
        <span>Home</span>
    </a>
    <a href="{{ route('search') }}"
       class="mobile-bottom-nav-item {{ $searchActive ? 'is-active' : '' }}"
       @if($searchActive) aria-current="page" @endif>
        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
        <span>Search</span>
    </a>
    <a href="{{ route('cart.index') }}"
       class="mobile-bottom-nav-item {{ $cartActive ? 'is-active' : '' }}"
       aria-label="Cart, {{ $cartCount }} items"
       @if($cartActive) aria-current="page" @endif>
        <span class="mobile-bottom-nav-icon">
            <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
            @if($cartCount > 0)
                <span class="mobile-cart-count">{{ $cartCount }}</span>
            @endif
        </span>
        <span>Cart</span>
    </a>
    <a href="{{ $profileUrl }}"
       class="mobile-bottom-nav-item {{ $profileActive ? 'is-active' : '' }}"
       @if($profileActive) aria-current="page" @endif>
        <i class="fa-regular fa-user" aria-hidden="true"></i>
        <span>Profile</span>
    </a>
</nav>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
// User dropdown toggle
const userMenu = document.getElementById('userMenu');
const userDropdown = document.getElementById('userDropdown');
if (userMenu && userDropdown) {
    userMenu.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.style.display = userDropdown.style.display === 'none' ? 'block' : 'none';
    });
    document.addEventListener('click', function() {
        if (userDropdown) userDropdown.style.display = 'none';
    });
}
</script>
@stack('scripts')
</body>
</html>