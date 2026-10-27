@extends('layouts.app')

@section('title', 'Forgot Password — GiftBloom')

@include('auth.partials.styles')
@include('auth.partials.scripts')

@section('content')
@include('auth.partials.stage-start')

            @include('auth.partials.visual', [
                'visualTitle' => 'Misplaced the key to your gifts?',
                'visualText' => "No worries — we'll help you unwrap access to your account again.",
            ])

            <div class="gb-auth-card">
                <a href="{{ url('/') }}" class="gb-auth-back">
                    <span aria-hidden="true">←</span> Back to Home
                </a>

                <div class="gb-auth-badge" aria-hidden="true">
                    <i class="fa-solid fa-envelope-open-text"></i>
                    <span class="gb-auth-star gb-auth-star--1">✦</span>
                    <span class="gb-auth-star gb-auth-star--2">✧</span>
                </div>

                <div class="gb-auth-card-head">
                    <h1>Forgot your password?</h1>
                    <p>Enter your email and we'll send a cozy little reset link.</p>
                </div>

                @if (session('status'))
                    <div class="gb-auth-alert gb-auth-alert--success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="gb-auth-alert gb-auth-alert--error" role="alert">
                        Please check your email address and try again.
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="gb-auth-field">
                        <label for="email">Email address</label>
                        <div class="gb-auth-input">
                            <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Enter your registered email"
                                class="@error('email') gb-input-invalid @enderror"
                                autocomplete="email"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
                            <small class="gb-auth-field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="gb-auth-submit">
                        Send Reset Link
                    </button>

                    <p class="gb-auth-footer">
                        Remember your password?
                        <a href="{{ route('login') }}">Sign in</a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
