@extends('layouts.app')

@section('title', 'My Profile')

@include('account.partials.styles')

@section('content')
<div class="gb-account-page">
    <div class="gb-account-container">
        <div class="gb-account-header">
            <h1>My Profile</h1>
            <p>Manage your GiftBloom account details.</p>
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
            @include('account.partials.nav', ['active' => 'profile'])

            <section class="gb-account-card">
                <h2>Account details</h2>
                <p class="gb-account-help">Update your name and phone number. Email is used for login and cannot be changed here.</p>

                <form method="POST" action="{{ route('account.profile.update') }}" class="gb-account-form">
                    @csrf
                    @method('PUT')

                    <div class="gb-field">
                        <label for="name">Full Name *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            maxlength="255"
                            autocomplete="name"
                        >
                        @error('name') <span class="gb-field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="gb-field">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            value="{{ $user->email }}"
                            readonly
                            disabled
                        >
                    </div>

                    <div class="gb-field">
                        <label for="phone">Phone</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            maxlength="20"
                            autocomplete="tel"
                            placeholder="10-digit mobile number"
                        >
                        @error('phone') <span class="gb-field-error">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="gb-account-save-btn">Save Changes</button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
