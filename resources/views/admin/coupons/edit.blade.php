@extends('layouts.admin')
@section('title', 'Edit Coupon')
@section('page-title', 'Edit Coupon')

@section('content')
<div class="admin-table-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="fa-solid fa-pen-to-square me-2" style="color:#7d8531;"></i>
            Edit Coupon
        </h5>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Coupons
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

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.coupons._form', ['coupon' => $coupon])

        <div class="mt-4 d-flex gap-2 flex-wrap">
            <button type="submit" class="btn-brand">
                <i class="fa-solid fa-floppy-disk"></i> Update Coupon
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
