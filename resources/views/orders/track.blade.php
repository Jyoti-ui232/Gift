@extends('layouts.app')

@section('title', 'Track Order')

@section('content')
<div class="gb-track-page">
    <div class="gb-track-container">
        <div class="gb-track-header">
            <h1>Track Order</h1>
            <p>Enter your order number and the phone number used at checkout.</p>
        </div>

        <div class="gb-track-card">
            <form method="POST" action="{{ route('order.track.submit') }}" class="gb-track-form">
                @csrf

                <div class="gb-field">
                    <label for="order_number">Order Number *</label>
                    <input
                        type="text"
                        id="order_number"
                        name="order_number"
                        value="{{ old('order_number') }}"
                        placeholder="e.g. GB202610210001"
                        required
                        maxlength="50"
                        autocomplete="off"
                    >
                    @error('order_number')
                        <span class="gb-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="gb-field">
                    <label for="phone">Phone Number *</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Phone used for the order"
                        required
                        maxlength="20"
                        autocomplete="tel"
                    >
                    @error('phone')
                        <span class="gb-field-error">{{ $message }}</span>
                    @enderror
                </div>

                @error('track')
                    <div class="gb-product-alert gb-product-alert-error">{{ $message }}</div>
                @enderror

                <button type="submit" class="gb-track-submit">Track Order</button>
            </form>

            @if(!empty($found) && !empty($order))
                <div class="gb-track-result">
                    <h2>Order Status</h2>

                    <div class="gb-track-meta">
                        <div>
                            <span>Order Number</span>
                            <strong>{{ $order->order_number }}</strong>
                        </div>
                        <div>
                            <span>Order Date</span>
                            <strong>{{ optional($order->created_at)->format('d M Y') }}</strong>
                        </div>
                        <div>
                            <span>Status</span>
                            <strong class="gb-track-status">{{ ucfirst($order->status) }}</strong>
                        </div>
                    </div>

                    @if($order->tracking_number || $order->courier_name || $order->tracking_url)
                        <div class="gb-track-shipment">
                            <h3>
                                <i class="fa-solid fa-truck" aria-hidden="true"></i>
                                Shipment Tracking
                            </h3>

                            @if($order->courier_name)
                                <p>Courier: <strong>{{ $order->courier_name }}</strong></p>
                            @endif

                            @if($order->tracking_number)
                                <p>Tracking No: <strong>{{ $order->tracking_number }}</strong></p>
                            @endif

                            @if($order->tracking_url)
                                <a href="{{ $order->tracking_url }}"
                                   class="gb-track-link"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    Track Shipment
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="gb-track-note">
                            Shipment tracking details will appear here once your order is dispatched.
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.gb-track-page {
    background: #f8f7f1;
    min-height: 55vh;
    padding: 28px 16px 48px;
}

.gb-track-container {
    max-width: 560px;
    margin: 0 auto;
}

.gb-track-header h1 {
    margin: 0 0 6px;
    font-size: 1.75rem;
    color: #303021;
}

.gb-track-header p {
    margin: 0 0 22px;
    color: #77776d;
    font-size: 0.95rem;
}

.gb-track-card {
    background: #fff;
    border: 1px solid #ecece5;
    border-radius: 12px;
    padding: 24px 22px;
}

.gb-track-form .gb-field {
    margin-bottom: 16px;
}

.gb-track-submit {
    width: 100%;
    min-height: 48px;
    border: 0;
    border-radius: 8px;
    background: #7d8531;
    color: #fff;
    font-weight: 700;
    font-size: 0.98rem;
    cursor: pointer;
}

.gb-track-submit:hover {
    background: #686f27;
}

.gb-track-result {
    margin-top: 26px;
    padding-top: 22px;
    border-top: 1px solid #ecece5;
}

.gb-track-result h2 {
    margin: 0 0 14px;
    font-size: 1.15rem;
    color: #303021;
}

.gb-track-meta {
    display: grid;
    gap: 12px;
    margin-bottom: 16px;
}

.gb-track-meta > div {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 8px;
    background: #f8faf0;
}

.gb-track-meta span {
    color: #77776d;
    font-size: 0.88rem;
}

.gb-track-meta strong {
    color: #303021;
    font-size: 0.92rem;
    text-align: right;
}

.gb-track-status {
    color: #7d8531 !important;
}

.gb-track-shipment {
    padding: 14px 16px;
    border: 1px solid #ecece5;
    border-radius: 10px;
    background: #fdfdfa;
}

.gb-track-shipment h3 {
    margin: 0 0 10px;
    font-size: 1rem;
    color: #303021;
}

.gb-track-shipment h3 i {
    color: #7d8531;
    margin-right: 6px;
}

.gb-track-shipment p {
    margin: 0 0 8px;
    color: #555;
    font-size: 0.9rem;
}

.gb-track-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 6px;
    min-height: 38px;
    padding: 0 16px;
    border-radius: 8px;
    background: #7d8531;
    color: #fff;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
}

.gb-track-link:hover {
    background: #686f27;
    color: #fff;
}

.gb-track-note {
    margin: 0;
    color: #77776d;
    font-size: 0.9rem;
    line-height: 1.5;
}

@media (max-width: 575px) {
    .gb-track-card {
        padding: 20px 16px;
    }

    .gb-track-meta > div {
        flex-direction: column;
        gap: 4px;
    }

    .gb-track-meta strong {
        text-align: left;
    }
}
</style>
@endpush
