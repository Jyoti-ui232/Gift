@extends('layouts.app')

@section('title', 'Create Account — GiftBloom')

@include('auth.partials.styles')
@include('auth.partials.scripts')

@section('content')
@include('auth.partials.stage-start')

            @include('auth.partials.visual', [
                'visualTitle' => 'Join the GiftBloom family',
                'visualText' => 'Save favourites, track deliveries, and make every celebration feel personal.',
            ])

            <div class="gb-auth-card gb-auth-register">
                <div class="gb-auth-badge" aria-hidden="true">
                    <i class="fa-solid fa-gift"></i>
                    <span class="gb-auth-star gb-auth-star--1">✦</span>
                    <span class="gb-auth-star gb-auth-star--2">✧</span>
                </div>

                <div class="gb-auth-card-head">
                    <h1>Create Your GiftBloom Account</h1>
                    <p>A little sign-up now, endless thoughtful gifting later.</p>
                </div>

                @if (session('status'))
                    <div class="gb-auth-alert gb-auth-alert--success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="gb-auth-alert gb-auth-alert--error" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="gb-auth-field">
                        <label for="name">Full Name</label>
                        <div class="gb-auth-input">
                            <i class="fa-regular fa-user" aria-hidden="true"></i>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="@error('name') is-invalid @enderror"
                                placeholder="Enter your full name"
                                autocomplete="name"
                                maxlength="255"
                                required
                                autofocus
                            >
                        </div>
                        @error('name')
                            <div class="gb-auth-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="gb-auth-field">
                        <label for="email">Email Address</label>
                        <div class="gb-auth-input">
                            <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="@error('email') is-invalid @enderror"
                                placeholder="name@example.com"
                                autocomplete="email"
                                required
                            >
                        </div>
                        @error('email')
                            <div class="gb-auth-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="gb-auth-field">
                        <label for="phone">Phone Number <small>(optional)</small></label>
                        <div class="gb-auth-input">
                            <i class="fa-solid fa-phone" aria-hidden="true"></i>
                            <input
                                id="phone"
                                type="tel"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="@error('phone') is-invalid @enderror"
                                placeholder="+91 98765 43210"
                                autocomplete="tel"
                                maxlength="20"
                            >
                        </div>
                        @error('phone')
                            <div class="gb-auth-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="gb-auth-field">
                        <label for="password">Password</label>
                        <div class="gb-auth-input">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="@error('password') is-invalid @enderror"
                                placeholder="Min. 8 characters"
                                autocomplete="new-password"
                                required
                            >
                            <button type="button" class="gb-auth-toggle-pass" data-target="password" aria-label="Show password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="gb-auth-field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="gb-auth-field">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="gb-auth-input">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Re-enter your password"
                                autocomplete="new-password"
                                required
                            >
                            <button type="button" class="gb-auth-toggle-pass" data-target="password_confirmation" aria-label="Show password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="gb-auth-submit">
                        Create Account
                    </button>

                    <p class="gb-auth-footer">
                        Already have an account?
                        <a href="{{ route('login') }}">Sign in</a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
