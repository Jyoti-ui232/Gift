<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — GiftBloom Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @stack('styles')
    <style>
        :root {
            --sidebar-bg: #2d3748;
            --sidebar-hover: #3a4a60;
            --sidebar-active: #4a5568;
            --brand-green: #7d8531;
            --brand-green-dark: #5a6123;
            --sidebar-width: 250px;
        }
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f1f5f9; }
        
        /* Sidebar */
        .admin-sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-width); height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            overflow-y: auto; z-index: 1000;
            transition: transform 0.25s ease;
        }
        .admin-sidebar-close,
        .admin-sidebar-toggle,
        .admin-sidebar-overlay {
            display: none;
        }
        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-decoration: none;
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: var(--brand-green);
            border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16px; margin-bottom: 6px;
        }
        .sidebar-brand .brand-name {
            display: block; color: #fff;
            font-size: 18px; font-weight: 700;
            letter-spacing: 0.3px;
        }
        .sidebar-brand .brand-sub {
            display: block; color: rgba(255,255,255,0.45);
            font-size: 10px; text-transform: uppercase; letter-spacing: 1px;
        }
        .sidebar-nav { padding: 12px 0; flex: 1; }
        .nav-section-label {
            padding: 10px 20px 4px;
            font-size: 10px; text-transform: uppercase;
            color: rgba(255,255,255,0.35); letter-spacing: 1.2px;
            font-weight: 600;
        }
        .sidebar-link {
            display: flex; align-items: center;
            padding: 10px 20px; color: rgba(255,255,255,0.72);
            text-decoration: none; font-size: 13.5px;
            transition: all 0.18s ease; border-left: 3px solid transparent;
            gap: 11px;
        }
        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: #fff; border-left-color: var(--brand-green);
        }
        .sidebar-link.active {
            background: var(--sidebar-active);
            color: #fff; border-left-color: var(--brand-green);
            font-weight: 600;
        }
        .sidebar-link i { width: 18px; text-align: center; font-size: 14px; }
        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px; margin-bottom: 12px;
        }
        .sidebar-user .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--brand-green);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 14px; font-weight: 700; flex-shrink: 0;
        }
        .sidebar-user .user-name {
            color: #fff; font-size: 13px; font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user .user-role {
            color: rgba(255,255,255,0.45); font-size: 11px;
        }
        .sidebar-logout-btn {
            display: flex; align-items: center; gap: 8px;
            width: 100%; padding: 8px 12px;
            background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25);
            color: #fc8181; border-radius: 6px;
            font-size: 12.5px; cursor: pointer; transition: all 0.18s;
        }
        .sidebar-logout-btn:hover {
            background: rgba(239,68,68,0.22); color: #fff;
        }

        /* Main area */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .admin-topbar {
            position: sticky; top: 0; z-index: 900;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 28px;
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .admin-topbar .page-title {
            font-size: 18px; font-weight: 700; color: #1a202c;
        }
        .admin-topbar .topbar-right {
            display: flex; align-items: center; gap: 14px;
        }
        .admin-content {
            padding: 28px; flex: 1;
        }

        /* Cards */
        .stat-card {
            background: #fff; border-radius: 12px;
            padding: 20px 22px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            transition: transform 0.18s, box-shadow 0.18s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 12px;
        }
        .stat-card .stat-value {
            font-size: 26px; font-weight: 800; color: #1a202c;
        }
        .stat-card .stat-label {
            font-size: 12px; color: #718096; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;
        }

        /* Admin table */
        .admin-table-card {
            background: #fff; border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .admin-table-card .table-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .admin-table-card .table-header h5 {
            margin: 0; font-size: 15px; font-weight: 700; color: #1a202c;
        }
        .admin-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .admin-table th {
            background: #f7fafc; padding: 11px 16px;
            text-align: left; font-size: 11px;
            text-transform: uppercase; letter-spacing: 0.6px;
            color: #718096; font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }
        .admin-table td {
            padding: 12px 16px; border-bottom: 1px solid #f0f0f0;
            color: #2d3748; vertical-align: middle;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: #f7fafc; }

        /* Badges */
        .badge-status { padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
        .badge-pending   { background: #fef3c7; color: #92400e; }
        .badge-confirmed { background: #dbeafe; color: #1e40af; }
        .badge-processing{ background: #e0e7ff; color: #3730a3; }
        .badge-shipped   { background: #d1fae5; color: #065f46; }
        .badge-delivered { background: #dcfce7; color: #166534; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-paid      { background: #dcfce7; color: #166534; }
        .badge-unpaid    { background: #fee2e2; color: #991b1b; }
        .badge-active    { background: #dcfce7; color: #166534; }
        .badge-inactive  { background: #f3f4f6; color: #6b7280; }
        .badge-approved  { background: #dcfce7; color: #166534; }
        .badge-review    { background: #fef3c7; color: #92400e; }

        /* Btn primary */
        .btn-brand {
            background: var(--brand-green); border: none;
            color: #fff; padding: 8px 16px; border-radius: 7px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background 0.18s;
        }
        .btn-brand:hover { background: var(--brand-green-dark); color: #fff; }
        
        /* Form styles */
        .admin-form-card {
            background: #fff; border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 24px;
        }
        .admin-form-card .section-title {
            font-size: 13px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.6px;
            color: var(--brand-green); margin-bottom: 16px;
            padding-bottom: 8px; border-bottom: 2px solid #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                box-shadow: 4px 0 18px rgba(0, 0, 0, 0.18);
            }
            .admin-sidebar.is-open {
                transform: translateX(0);
            }
            .admin-main { margin-left: 0; }
            .admin-topbar {
                padding: 0 14px;
                gap: 10px;
            }
            .admin-topbar .page-title {
                font-size: 16px;
                flex: 1;
                min-width: 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .admin-content { padding: 18px 14px; }
            .admin-sidebar-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                flex-shrink: 0;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #fff;
                color: #1a202c;
                cursor: pointer;
            }
            .admin-sidebar-close {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                position: absolute;
                top: 14px;
                right: 12px;
                width: 32px;
                height: 32px;
                border: none;
                border-radius: 6px;
                background: rgba(255, 255, 255, 0.1);
                color: #fff;
                cursor: pointer;
            }
            .admin-sidebar-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.45);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.25s ease, visibility 0.25s ease;
            }
            .admin-sidebar-overlay.is-open {
                opacity: 1;
                visibility: visible;
            }
            body.admin-sidebar-open {
                overflow: hidden;
            }
        }
    </style>
</head>
<body>

<div class="admin-sidebar-overlay" id="adminSidebarOverlay" hidden></div>

<div class="admin-sidebar" id="adminSidebar">
    <button type="button" class="admin-sidebar-close" id="adminSidebarClose" aria-label="Close menu">
        <i class="fa-solid fa-xmark"></i>
    </button>

    <!-- Brand -->
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand" style="text-decoration:none;">
        <div class="brand-icon"><i class="fa-solid fa-gift"></i></div>
        <span class="brand-name">GiftBloom</span>
        <span class="brand-sub">Admin Panel</span>
    </a>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>

        <div class="nav-section-label">Catalog</div>
        <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open"></i> Products
        </a>
        <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fa-solid fa-tags"></i> Categories
        </a>
        <a href="{{ route('admin.occasions.index') }}" class="sidebar-link {{ request()->routeIs('admin.occasions.*') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-days"></i> Occasions
        </a>

        <div class="nav-section-label">Sales</div>
        <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="fa-solid fa-cart-shopping"></i> Orders
        </a>
        <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i> Customers
        </a>
        <a href="{{ route('admin.coupons.index') }}" class="sidebar-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="fa-solid fa-ticket"></i> Coupons
        </a>

        <div class="nav-section-label">Content</div>
        <a href="{{ route('admin.banners.index') }}" class="sidebar-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
            <i class="fa-solid fa-image"></i> Banners
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="fa-solid fa-star"></i> Reviews
        </a>

        <div class="nav-section-label">Config</div>
        <a href="{{ route('admin.delivery-slots.index') }}" class="sidebar-link {{ request()->routeIs('admin.delivery-slots.*') ? 'active' : '' }}">
            <i class="fa-solid fa-truck-fast"></i> Delivery Slots
        </a>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i> Settings
        </a>
    </nav>

    <!-- Footer / User -->
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Sign Out
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="admin-main">
    <!-- Top Bar -->
    <div class="admin-topbar">
        <button type="button" class="admin-sidebar-toggle" id="adminSidebarToggle" aria-label="Open menu" aria-controls="adminSidebar" aria-expanded="false">
            <i class="fa-solid fa-bars"></i>
        </button>
        <span class="page-title">@yield('page-title', 'Dashboard')</span>
        <div class="topbar-right">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View Site">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Site
            </a>
            <span class="text-muted" style="font-size:12px;">{{ now()->format('d M Y') }}</span>
        </div>
    </div>

    <!-- Flash Messages -->
    <div style="padding: 0 28px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert" style="border-radius:8px;">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" style="border-radius:8px;">
                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" style="border-radius:8px;">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <div class="admin-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var sidebar = document.getElementById('adminSidebar');
    var overlay = document.getElementById('adminSidebarOverlay');
    var toggleBtn = document.getElementById('adminSidebarToggle');
    var closeBtn = document.getElementById('adminSidebarClose');

    if (!sidebar || !overlay || !toggleBtn || !closeBtn) {
        return;
    }

    function isMobileSidebar() {
        return window.matchMedia('(max-width: 768px)').matches;
    }

    function setSidebarOpen(isOpen) {
        sidebar.classList.toggle('is-open', isOpen);
        overlay.classList.toggle('is-open', isOpen);
        document.body.classList.toggle('admin-sidebar-open', isOpen);
        toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        toggleBtn.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
        overlay.hidden = !isOpen;
    }

    function closeSidebar() {
        setSidebarOpen(false);
    }

    function openSidebar() {
        if (!isMobileSidebar()) {
            return;
        }
        setSidebarOpen(true);
    }

    toggleBtn.addEventListener('click', function () {
        if (sidebar.classList.contains('is-open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    window.addEventListener('resize', function () {
        if (!isMobileSidebar()) {
            closeSidebar();
        }
    });
})();
</script>
@stack('scripts')
</body>
</html>
