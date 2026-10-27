@extends('layouts.app')

@section('title', 'My Addresses')

@include('account.partials.styles')

@section('content')
<div class="gb-account-page">
    <div class="gb-account-container">
        <div class="gb-account-header">
            <h1>My Addresses</h1>
            <p>Save delivery addresses for faster checkout.</p>
        </div>

        @if(session('success'))
            <div class="gb-product-alert gb-product-alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="gb-product-alert gb-product-alert-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="gb-product-alert gb-product-alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="gb-account-layout">
            @include('account.partials.nav', ['active' => 'addresses'])

            <div class="gb-account-stack">
                <section class="gb-account-card">
                    <h2>Add new address</h2>
                    <p class="gb-account-help">Enter the recipient and delivery details below.</p>

                    <form method="POST" action="{{ route('account.addresses.store') }}" class="gb-account-form">
                        @csrf
                        @include('account.partials.address-fields', ['address' => null])
                        <button type="submit" class="gb-account-save-btn">Save Address</button>
                    </form>
                </section>

                <section class="gb-account-card">
                    <h2>Saved addresses</h2>
                    <p class="gb-account-help">
                        {{ $addresses->count() }}
                        {{ $addresses->count() === 1 ? 'address' : 'addresses' }} saved.
                    </p>

                    @if($addresses->isEmpty())
                        <div class="gb-account-empty">
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                            <p>You have no saved addresses yet.</p>
                        </div>
                    @else
                        <div class="gb-address-list">
                            @foreach($addresses as $address)
                                <div class="gb-address-item">
                                    <div class="gb-address-item-top">
                                        <div>
                                            <strong>{{ $address->name }}</strong>
                                            @if($address->is_default)
                                                <span class="gb-address-default">Default</span>
                                            @endif
                                            <div class="gb-order-meta" style="margin-top:6px;">
                                                {{ $address->phone }}
                                            </div>
                                            <div class="gb-order-meta" style="margin-top:4px;">
                                                {{ $address->full_address }}
                                                @if($address->country)
                                                    , {{ $address->country }}
                                                @endif
                                            </div>
                                            @if($address->type)
                                                <div class="gb-order-meta" style="margin-top:4px;">
                                                    Type: {{ ucfirst($address->type) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="gb-address-actions">
                                            <button type="button"
                                                    class="gb-address-edit-btn"
                                                    data-address-toggle="{{ $address->id }}"
                                                    aria-expanded="false">
                                                Edit
                                            </button>
                                            <form method="POST" action="{{ route('account.addresses.destroy', $address) }}" onsubmit="return confirm('Delete this address?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="gb-wishlist-remove">Delete</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="gb-address-edit-panel" id="address-edit-{{ $address->id }}" hidden>
                                        <form method="POST" action="{{ route('account.addresses.update', $address) }}" class="gb-account-form">
                                            @csrf
                                            @method('PUT')
                                            @include('account.partials.address-fields', ['address' => $address])
                                            <button type="submit" class="gb-account-save-btn">Update Address</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-address-toggle]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id = btn.getAttribute('data-address-toggle');
        var panel = document.getElementById('address-edit-' + id);
        if (!panel) return;
        var willOpen = panel.hasAttribute('hidden');
        panel.toggleAttribute('hidden', !willOpen);
        btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        btn.textContent = willOpen ? 'Close' : 'Edit';
    });
});
</script>
@endpush
