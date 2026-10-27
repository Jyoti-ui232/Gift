@extends('layouts.admin')
@section('title', 'Add Delivery Slot')
@section('page-title', 'Add Delivery Slot')

@section('content')
<div class="admin-table-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="fa-solid fa-truck-fast me-2" style="color:#7d8531;"></i>
            Add Delivery Slot
        </h5>
        <a href="{{ route('admin.delivery-slots.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Slots
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

    <form action="{{ route('admin.delivery-slots.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Slot Name *</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name') }}" required maxlength="255"
                       placeholder="e.g. Morning Delivery">
            </div>

            <div class="col-md-6">
                <label class="form-label">Time Range *</label>
                <input type="text" name="time_range" class="form-control"
                       value="{{ old('time_range') }}" required maxlength="255"
                       placeholder="e.g. 9 AM - 12 PM">
            </div>

            <div class="col-md-6">
                <label class="form-label">Type *</label>
                <select name="type" class="form-select" required>
                    @foreach(['standard' => 'Standard', 'express' => 'Express', 'midnight' => 'Midnight', 'fixed' => 'Fixed'] as $value => $label)
                        <option value="{{ $value }}" {{ old('type', 'standard') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Extra Charge (₹)</label>
                <input type="number" name="extra_charge" class="form-control"
                       value="{{ old('extra_charge', 0) }}" min="0" step="0.01">
            </div>

            <div class="col-md-3">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control"
                       value="{{ old('sort_order', 0) }}" min="0">
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2 flex-wrap">
            <button type="submit" class="btn-brand">
                <i class="fa-solid fa-floppy-disk"></i> Save Slot
            </button>
            <a href="{{ route('admin.delivery-slots.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
