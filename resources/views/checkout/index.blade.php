@extends('layouts.app')

@section('title', 'Checkout')

@php
    $freeDeliveryMin = \App\Http\Controllers\CheckoutController::FREE_DELIVERY_MIN;
    $defaultDate = old('delivery_date', now()->addDay()->format('Y-m-d'));
@endphp

@section('content')
<div class="gb-checkout-page">
    <div class="gb-checkout-container">
        <div class="gb-checkout-header">
            <h1>Checkout</h1>
            <p>Complete your gift delivery details and place your order.</p>
        </div>

        @if(session('success'))
            <div class="gb-product-alert gb-product-alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="gb-product-alert gb-product-alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="gb-product-alert gb-product-alert-error">
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" class="gb-checkout-layout" id="checkoutForm" novalidate>
            @csrf

            <div class="gb-checkout-main">
                {{-- Delivery Address --}}
                <section class="gb-checkout-card">
                    <h2>Delivery Address</h2>

                    @if($addresses->count())
                        <div class="gb-saved-addresses">
                            <p class="gb-checkout-help">Use a saved address:</p>
                            <div class="gb-saved-address-list">
                                @foreach($addresses as $address)
                                    <button type="button"
                                            class="gb-saved-address-btn {{ $defaultAddress && $defaultAddress->id === $address->id ? 'is-active' : '' }}"
                                            data-name="{{ $address->name }}"
                                            data-phone="{{ $address->phone }}"
                                            data-address="{{ $address->address_line1 }}{{ $address->address_line2 ? ', '.$address->address_line2 : '' }}"
                                            data-city="{{ $address->city }}"
                                            data-state="{{ $address->state }}"
                                            data-pincode="{{ $address->pincode }}">
                                        <strong>{{ $address->name }}</strong>
                                        <span>{{ $address->full_address }}</span>
                                        <span>{{ $address->phone }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="gb-checkout-grid">
                        <div class="gb-field">
                            <label for="recipient_name">Full Name *</label>
                            <input type="text"
                                   id="recipient_name"
                                   name="recipient_name"
                                   value="{{ old('recipient_name', $defaultAddress->name ?? auth()->user()->name) }}"
                                   required
                                   maxlength="255">
                            @error('recipient_name') <span class="gb-field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="gb-field">
                            <label for="recipient_phone">Phone *</label>
                            <input type="tel"
                                   id="recipient_phone"
                                   name="recipient_phone"
                                   value="{{ old('recipient_phone', $defaultAddress->phone ?? auth()->user()->phone) }}"
                                   required
                                   maxlength="10"
                                   inputmode="numeric"
                                   placeholder="10-digit mobile number">
                            @error('recipient_phone') <span class="gb-field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="gb-field gb-field-full">
                            <label for="recipient_address">Address *</label>
                            <textarea id="recipient_address"
                                      name="recipient_address"
                                      rows="3"
                                      required
                                      maxlength="500"
                                      placeholder="House no., street, landmark">{{ old('recipient_address', $defaultAddress->address_line1 ?? '') }}</textarea>
                            @error('recipient_address') <span class="gb-field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="gb-field">
                            <label for="recipient_city">City *</label>
                            <input type="text"
                                   id="recipient_city"
                                   name="recipient_city"
                                   value="{{ old('recipient_city', $defaultAddress->city ?? '') }}"
                                   required
                                   maxlength="100">
                            @error('recipient_city') <span class="gb-field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="gb-field">
                            <label for="recipient_state">State *</label>
                            <input type="text"
                                   id="recipient_state"
                                   name="recipient_state"
                                   value="{{ old('recipient_state', $defaultAddress->state ?? '') }}"
                                   required
                                   maxlength="100">
                            @error('recipient_state') <span class="gb-field-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="gb-field">
                            <label for="recipient_pincode">Pincode *</label>
                            <input type="text"
                                   id="recipient_pincode"
                                   name="recipient_pincode"
                                   value="{{ old('recipient_pincode', $defaultAddress->pincode ?? '') }}"
                                   required
                                   maxlength="6"
                                   inputmode="numeric"
                                   placeholder="6-digit pincode">
                            @error('recipient_pincode') <span class="gb-field-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <label class="gb-check-row">
                        <input type="checkbox" name="save_address" value="1" {{ old('save_address') ? 'checked' : '' }}>
                        <span>Save this address to my account</span>
                    </label>
                </section>

                {{-- Delivery Preferences --}}
                <section class="gb-checkout-card">
                    <h2>Delivery Details</h2>

                    <div class="gb-checkout-grid">
                        <div class="gb-field">
                            <label for="delivery_date">Delivery Date *</label>
                            <input type="date"
                                   id="delivery_date"
                                   name="delivery_date"
                                   value="{{ $defaultDate }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   required>
                            @error('delivery_date') <span class="gb-field-error">{{ $message }}</span> @enderror
                        </div>

                        @if($deliverySlots->count())
                            <div class="gb-field">
                                <label for="delivery_slot_id">Delivery Slot *</label>
                                <select id="delivery_slot_id" name="delivery_slot_id" required>
                                    <option value="">Select a slot</option>
                                    @foreach($deliverySlots as $slot)
                                        <option value="{{ $slot->id }}"
                                                data-charge="{{ $slot->extra_charge }}"
                                                {{ (string) old('delivery_slot_id') === (string) $slot->id ? 'selected' : '' }}>
                                            {{ $slot->name }} ({{ $slot->time_range }})
                                            @if($slot->extra_charge > 0)
                                                — +₹{{ number_format($slot->extra_charge) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('delivery_slot_id') <span class="gb-field-error">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="gb-field gb-field-full">
                            <label for="gift_message">Gift Message (Optional)</label>
                            <textarea id="gift_message"
                                      name="gift_message"
                                      rows="2"
                                      maxlength="500"
                                      placeholder="A short note for the recipient">{{ old('gift_message', $cartGiftMessage ?? '') }}</textarea>
                        </div>

                        <div class="gb-field gb-field-full">
                            <label for="special_instructions">Special Instructions (Optional)</label>
                            <textarea id="special_instructions"
                                      name="special_instructions"
                                      rows="2"
                                      maxlength="500"
                                      placeholder="Gate code, landmark, preferred time notes">{{ old('special_instructions') }}</textarea>
                        </div>
                    </div>
                </section>

                {{-- Payment --}}
                <section class="gb-checkout-card">
                    <h2>Payment Method</h2>
                    <p class="gb-checkout-help">
                        Online payment is not integrated yet. Orders are accepted with Cash on Delivery only.
                    </p>

                    <label class="gb-payment-option is-selected">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <div>
                            <strong>Cash on Delivery (COD)</strong>
                            <span>Pay when your gift is delivered.</span>
                        </div>
                    </label>
                    @error('payment_method') <span class="gb-field-error">{{ $message }}</span> @enderror
                </section>
            </div>

            {{-- Order Summary --}}
            <aside class="gb-checkout-summary">
                <div class="gb-checkout-card gb-summary-sticky">
                    <h2>Order Summary</h2>

                    <div class="gb-summary-items">
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->product;
                                $image = $product?->images?->firstWhere('is_primary', true)
                                    ?? $product?->images?->first();
                                $imageSrc = $image?->url ?? ($product?->thumbnail_url ?? asset('assets/image/roses.jpg'));
                                $unitPrice = $item->unit_price;
                            @endphp
                            <div class="gb-summary-item">
                                <img src="{{ $imageSrc }}" alt="{{ $product->name }}">
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    @if($item->variant)
                                        <span>{{ $item->variant->name }}</span>
                                    @endif
                                    @if($item->gift_message)
                                        <span>Gift message: {{ $item->gift_message }}</span>
                                    @endif
                                    <span>Qty: {{ $item->quantity }} × ₹{{ number_format($unitPrice) }}</span>
                                </div>
                                <div class="gb-summary-item-total">₹{{ number_format($item->total) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="gb-cart-row">
                        <span>Subtotal</span>
                        <strong>₹{{ number_format($subtotal) }}</strong>
                    </div>

                    @if($discount > 0)
                        <div class="gb-cart-row">
                            <span>Discount {{ $coupon?->code ? '('.$coupon->code.')' : '' }}</span>
                            <strong>-₹{{ number_format($discount) }}</strong>
                        </div>
                    @endif

                    <div class="gb-cart-row">
                        <span>Delivery</span>
                        <strong>
                            @if($deliveryCharge > 0)
                                ₹{{ number_format($deliveryCharge) }}
                            @else
                                FREE
                            @endif
                        </strong>
                    </div>

                    @if($subtotal < $freeDeliveryMin)
                        <p class="gb-checkout-help">
                            Add ₹{{ number_format($freeDeliveryMin - $subtotal) }} more for free delivery.
                        </p>
                    @endif

                    <div class="gb-cart-row gb-cart-total">
                        <span>Total Payable</span>
                        <strong>₹{{ number_format($total) }}</strong>
                    </div>

                    <button type="submit" class="gb-checkout-place-btn">
                        Place Order · ₹{{ number_format($total) }}
                    </button>

                    <a href="{{ route('cart.index') }}" class="gb-checkout-back">Back to Cart</a>
                </div>
            </aside>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    document.querySelectorAll('.gb-saved-address-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.gb-saved-address-btn').forEach(function (b) {
                b.classList.remove('is-active');
            });
            btn.classList.add('is-active');

            document.getElementById('recipient_name').value = btn.dataset.name || '';
            document.getElementById('recipient_phone').value = btn.dataset.phone || '';
            document.getElementById('recipient_address').value = btn.dataset.address || '';
            document.getElementById('recipient_city').value = btn.dataset.city || '';
            document.getElementById('recipient_state').value = btn.dataset.state || '';
            document.getElementById('recipient_pincode').value = btn.dataset.pincode || '';
        });
    });
})();
</script>
@endpush
