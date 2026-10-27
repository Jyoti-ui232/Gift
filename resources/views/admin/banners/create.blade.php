@extends('layouts.admin')
@section('title', 'Add Banner')
@section('page-title', 'Add Banner')

@section('content')
<div class="admin-table-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="fa-solid fa-image me-2" style="color:#7d8531;"></i>
            Add Banner
        </h5>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Banners
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix these errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.banners._form', ['banner' => null])

        <div class="mt-4 d-flex gap-2 flex-wrap">
            <button type="submit" class="btn-brand">
                <i class="fa-solid fa-floppy-disk"></i> Save Banner
            </button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
