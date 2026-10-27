@extends('layouts.app')

@section('title', 'Sign In — GiftBloom')

@include('auth.partials.styles')
@include('auth.partials.scripts')

@section('content')
@include('auth.partials.stage-start')

            @include('auth.partials.visual', [
                'visualTitle' => 'Every login unwraps a little joy',
                'visualText' => 'Pick up where you left off — wishlists, orders, and gifts waiting to bloom.',
            ])

            <div class="gb-auth-card">
                <div class="gb-auth-badge" aria-hidden="true">
                    <i class="fa-solid fa-gift"></i>
                    <span class="gb-auth-star gb-auth-star--1">✦</span>
                    <span class="gb-auth-star gb-auth-star--2">✧</span>
                </div>

                <div class="gb-auth-card-head">
                    <h1>Welcome Back, Gift Lover!</h1>
                    <p>Sign in to continue your GiftBloom journey.</p>
                </div>

                @if (session('status'))
                    <div class="gb-auth-alert gb-auth-alert--success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="gb-auth-alert gb-auth-alert--error" role="alert">
                        Please check your email and password.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="gb-auth-field">
                        <label for="email">Email</label>
                        <div class="gb-auth-input">
                            <i class="fa-regular fa-user" aria-hidden="true"></i>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="@error('email') is-invalid @enderror"
                                placeholder="Username / Email"
                                autocomplete="email"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
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
                                placeholder="Password"
                                autocomplete="current-password"
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

                    <div class="gb-auth-row">
                        <label class="gb-auth-remember">
                            <input type="checkbox" name="remember" value="1"
                                {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>

                        @if (Route::has('password.request'))
                            <a class="gb-auth-link" href="{{ route('password.request') }}">Forgot Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="gb-auth-submit">
                        Login
                    </button>

                    <p class="gb-auth-footer">
                        Don't have an account?
                        <a href="{{ route('register') }}">Sign Up</a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
