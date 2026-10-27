@extends('layouts.app')

@section('title', 'Reset Password — GiftBloom')

@include('auth.partials.styles')
@include('auth.partials.scripts')

@section('content')
@include('auth.partials.stage-start')

            @include('auth.partials.visual', [
                'visualTitle' => 'A fresh start, beautifully wrapped',
                'visualText' => 'Choose a strong new password and get back to gifting in a moment.',
            ])

            <div class="gb-auth-card">
                <a href="{{ url('/') }}" class="gb-auth-back">
                    <span aria-hidden="true">←</span> Back to Home
                </a>

                <div class="gb-auth-badge" aria-hidden="true">
                    <i class="fa-solid fa-lock"></i>
                    <span class="gb-auth-star gb-auth-star--1">✦</span>
                    <span class="gb-auth-star gb-auth-star--2">✧</span>
                </div>

                <div class="gb-auth-card-head">
                    <h1>Reset your password</h1>
                    <p>Secure your GiftBloom account with a new password.</p>
                </div>

                @if (session('status'))
                    <div class="gb-auth-alert gb-auth-alert--success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="gb-auth-alert gb-auth-alert--error" role="alert">
                        Please check the details entered below.
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="gb-auth-field">
                        <label for="email">Email Address</label>
                        <div class="gb-auth-input">
                            <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $email ?? '') }}"
                                placeholder="Enter your registered email"
                                required
                                autocomplete="email"
                                autofocus
                                class="@error('email') is-invalid @enderror"
                            >
                        </div>
                        @error('email')
                            <span class="gb-auth-field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="gb-auth-field">
                        <label for="password">New Password</label>
                        <div class="gb-auth-input">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Enter your new password"
                                required
                                autocomplete="new-password"
                                class="@error('password') is-invalid @enderror"
                            >
                            <button type="button" class="gb-auth-toggle-pass" data-target="password" aria-label="Show password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="gb-auth-field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="gb-auth-field">
                        <label for="password_confirmation">Confirm New Password</label>
                        <div class="gb-auth-input">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Re-enter your new password"
                                required
                                autocomplete="new-password"
                            >
                            <button type="button" class="gb-auth-toggle-pass" data-target="password_confirmation" aria-label="Show password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="gb-auth-submit">
                        Reset Password
                    </button>

                    <p class="gb-auth-footer">
                        Remember your password?
                        <a href="{{ route('login') }}">Login here</a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
