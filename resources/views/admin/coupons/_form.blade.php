@php
    $coupon = $coupon ?? null;
    $startsAt = old('starts_at', optional($coupon?->starts_at)->format('Y-m-d\TH:i'));
    $expiresAt = old('expires_at', optional($coupon?->expires_at)->format('Y-m-d\TH:i'));
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Coupon Code *</label>
        <input type="text" name="code" class="form-control text-uppercase"
               value="{{ old('code', $coupon->code ?? '') }}" required maxlength="50"
               placeholder="e.g. SAVE10">
        @error('code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Discount Type *</label>
        <select name="type" class="form-select" required>
            <option value="percentage" {{ old('type', $coupon->type ?? 'percentage') === 'percentage' ? 'selected' : '' }}>
                Percentage (%)
            </option>
            <option value="fixed" {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>
                Fixed amount (₹)
            </option>
        </select>
        @error('type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Discount Value *</label>
        <input type="number" name="value" class="form-control"
               value="{{ old('value', $coupon->value ?? '') }}" required min="0" step="0.01">
        @error('value') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Minimum Order (₹)</label>
        <input type="number" name="min_order_amount" class="form-control"
               value="{{ old('min_order_amount', $coupon->min_order_amount ?? 0) }}" min="0" step="0.01">
        @error('min_order_amount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Max Discount (₹)</label>
        <input type="number" name="max_discount" class="form-control"
               value="{{ old('max_discount', $coupon->max_discount ?? '') }}" min="0" step="0.01"
               placeholder="Optional, for % coupons">
        @error('max_discount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Usage Limit</label>
        <input type="number" name="usage_limit" class="form-control"
               value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" min="1"
               placeholder="Leave blank for unlimited">
        @error('usage_limit') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Per User Limit</label>
        <input type="number" name="per_user_limit" class="form-control"
               value="{{ old('per_user_limit', $coupon->per_user_limit ?? 1) }}" min="1">
        @error('per_user_limit') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        @if($coupon)
            <label class="form-label">Times Used</label>
            <input type="text" class="form-control" value="{{ $coupon->used_count }}" readonly disabled>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">Starts At</label>
        <input type="datetime-local" name="starts_at" class="form-control" value="{{ $startsAt }}">
        @error('starts_at') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Expires At</label>
        <input type="datetime-local" name="expires_at" class="form-control" value="{{ $expiresAt }}">
        @error('expires_at') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"
                  maxlength="1000">{{ old('description', $coupon->description ?? '') }}</textarea>
        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>
