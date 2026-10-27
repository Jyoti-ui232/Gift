@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Website Settings</h1>
            <p class="text-muted mb-0">Manage your GiftBloom website settings.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                @forelse($settings as $key => $value)
                    <div class="mb-3">
                        <label for="setting_{{ $key }}" class="form-label">
                            {{ ucwords(str_replace(['_', '-'], ' ', $key)) }}
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="setting_{{ $key }}"
                            name="{{ $key }}"
                            value="{{ old($key, $value) }}"
                        >
                    </div>
                @empty
                    <p class="text-muted mb-3">
                        Abhi database mein koi settings nahi hain.
                    </p>
                @endforelse

                <button type="submit" class="btn btn-primary">
                    Save Settings
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
