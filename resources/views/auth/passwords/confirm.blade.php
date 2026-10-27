@extends('layouts.app')

@section('content')
<div class="gb-confirm-page">

    {{-- Confirm Password Banner --}}
    <div class="gb-login-banner" style="background: url('{{ asset('assets/image/login-banner.jpg') }}') center / cover no-repeat !important;">
        <div>
            <h1>Confirm Password</h1>
            <p>
                <a href="{{ url('/') }}">Home</a> &nbsp;—&nbsp; Confirm Password
            </p>
        </div>
    </div>

    {{-- Confirm Password Form --}}
    <div class="gb-confirm-card">

        <div class="gb-confirm-icon"><i class="fa-solid fa-lock"></i></div>

        <h2>Confirm Your Password</h2>

        <p class="gb-confirm-description">
            Please confirm your password before continuing.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="gb-confirm-field">
                <label for="password">Password</label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    class="@error('password') is-invalid @enderror"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                >

                @error('password')
                    <span class="gb-confirm-error" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <button type="submit" class="gb-confirm-button">
                Confirm Password
            </button>

            @if (Route::has('password.request'))
                <a class="gb-confirm-forgot" href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>
            @endif
        </form>

    </div>
</div>
@endsection