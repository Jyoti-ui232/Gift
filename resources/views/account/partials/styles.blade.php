@push('styles')
<style>
.gb-account-page {
    background: #f8f7f1;
    min-height: 50vh;
    padding: 28px 16px 48px;
}

.gb-account-container {
    max-width: 980px;
    margin: 0 auto;
}

.gb-account-header h1 {
    margin: 0 0 6px;
    font-size: 1.75rem;
    color: #303021;
}

.gb-account-header p {
    margin: 0 0 22px;
    color: #77776d;
    font-size: 0.95rem;
}

.gb-account-layout {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 20px;
    align-items: start;
}

.gb-account-stack {
    display: flex;
    flex-direction: column;
    gap: 18px;
    min-width: 0;
}

.gb-address-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.gb-address-item {
    padding: 14px;
    border: 1px solid #ecece5;
    border-radius: 10px;
    background: #fdfdfa;
}

.gb-address-item-top {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: flex-start;
}

.gb-address-default {
    display: inline-block;
    margin-left: 8px;
    padding: 2px 8px;
    border-radius: 999px;
    background: #f1f2e5;
    color: #7d8531;
    font-size: 0.72rem;
    font-weight: 700;
    vertical-align: middle;
}

.gb-address-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: stretch;
    flex-shrink: 0;
}

.gb-address-edit-btn,
.gb-address-actions .gb-wishlist-remove {
    min-height: 36px;
    padding: 0 14px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
}

.gb-address-edit-btn {
    border: 1px solid #d7d8c8;
    background: #fff;
    color: #444;
}

.gb-address-edit-panel {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid #ecece5;
}

.gb-address-check {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: #444;
    cursor: pointer;
}

.gb-account-form select {
    width: 100%;
    min-height: 44px;
    padding: 0 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    color: #333;
    font-size: 0.95rem;
}

.gb-account-nav {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #ecece5;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.gb-account-nav a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 12px;
    border-radius: 8px;
    color: #444;
    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 600;
}

.gb-account-nav a:hover,
.gb-account-nav a.is-active {
    background: #f1f2e5;
    color: #7d8531;
}

.gb-account-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #ecece5;
    padding: 24px 26px;
}

.gb-account-card h2 {
    margin: 0 0 6px;
    font-size: 1.2rem;
    color: #303021;
}

.gb-account-help {
    margin: 0 0 22px;
    color: #77776d;
    font-size: 0.88rem;
    line-height: 1.5;
}

.gb-account-form .gb-field {
    margin-bottom: 16px;
}

.gb-account-form .gb-field input:disabled,
.gb-account-form .gb-field input[readonly] {
    background: #f5f5f0;
    color: #666;
    cursor: not-allowed;
}

.gb-account-save-btn,
.gb-account-browse-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 6px;
    min-height: 46px;
    padding: 0 22px;
    border: 0;
    border-radius: 8px;
    background: #7d8531;
    color: #fff;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    text-decoration: none;
}

.gb-account-save-btn:hover,
.gb-account-browse-btn:hover {
    background: #686f27;
    color: #fff;
}

.gb-account-empty {
    text-align: center;
    padding: 36px 16px 28px;
}

.gb-account-empty i {
    font-size: 2rem;
    color: #7d8531;
    margin-bottom: 12px;
}

.gb-account-empty p {
    margin: 0 0 18px;
    color: #77776d;
    font-size: 0.95rem;
}

.gb-wishlist-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.gb-wishlist-item {
    display: grid;
    grid-template-columns: 88px 1fr auto;
    gap: 14px;
    align-items: center;
    padding: 12px;
    border: 1px solid #ecece5;
    border-radius: 10px;
    background: #fdfdfa;
}

.gb-wishlist-image {
    width: 88px;
    height: 88px;
    border-radius: 8px;
    overflow: hidden;
    background: #f1f2e5;
    display: block;
}

.gb-wishlist-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.gb-wishlist-info {
    min-width: 0;
}

.gb-wishlist-name {
    display: block;
    margin-bottom: 6px;
    color: #303021;
    font-weight: 700;
    font-size: 0.98rem;
    text-decoration: none;
}

.gb-wishlist-name:hover {
    color: #7d8531;
}

.gb-wishlist-price {
    color: #7d8531;
    font-weight: 700;
    font-size: 0.95rem;
}

.gb-wishlist-price .old {
    margin-left: 8px;
    color: #999;
    font-weight: 500;
    font-size: 0.85rem;
    text-decoration: line-through;
}

.gb-wishlist-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: stretch;
}

.gb-wishlist-view {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 36px;
    padding: 0 12px;
    border-radius: 8px;
    background: #7d8531;
    color: #fff;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}

.gb-wishlist-view:hover {
    background: #686f27;
    color: #fff;
}

.gb-wishlist-remove {
    min-height: 36px;
    padding: 0 12px;
    border: 1px solid #e5e5dc;
    border-radius: 8px;
    background: #fff;
    color: #77776d;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
}

.gb-wishlist-remove:hover {
    border-color: #e5b4b4;
    color: #c23b5a;
}

.gb-orders-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.gb-order-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 10px;
    padding: 14px 16px;
    border: 1px solid #ecece5;
    border-radius: 10px;
    background: #fdfdfa;
    text-decoration: none;
    color: inherit;
}

.gb-order-row:hover {
    border-color: #cfd4a8;
}

.gb-order-row strong {
    color: #303021;
}

.gb-order-meta {
    color: #77776d;
    font-size: 0.85rem;
}

.gb-order-total {
    color: #7d8531;
    font-weight: 700;
}

@media (max-width: 767px) {
    .gb-account-layout {
        grid-template-columns: 1fr;
    }

    .gb-account-nav {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .gb-account-nav a {
        flex: 1 1 auto;
        justify-content: center;
        font-size: 0.82rem;
        padding: 10px 8px;
    }

    .gb-account-card {
        padding: 20px 16px;
    }

    .gb-wishlist-item {
        grid-template-columns: 72px 1fr;
    }

    .gb-wishlist-image {
        width: 72px;
        height: 72px;
    }

    .gb-wishlist-actions {
        grid-column: 1 / -1;
        flex-direction: row;
    }

    .gb-wishlist-view,
    .gb-wishlist-remove {
        flex: 1;
    }

    .gb-address-item-top {
        flex-direction: column;
    }

    .gb-address-actions {
        flex-direction: row;
        width: 100%;
    }

    .gb-address-edit-btn,
    .gb-address-actions .gb-wishlist-remove {
        flex: 1;
    }
}
</style>
@endpush
