@php
    $banner = $banner ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Title *</label>
        <input type="text" name="title" class="form-control"
               value="{{ old('title', $banner->title ?? '') }}" required maxlength="255">
        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Subtitle</label>
        <input type="text" name="subtitle" class="form-control"
               value="{{ old('subtitle', $banner->subtitle ?? '') }}" maxlength="255">
        @error('subtitle') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Banner Image {{ $banner ? '' : '*' }}</label>
        @if($banner?->image)
            <div class="mb-2">
                <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}"
                     style="width:140px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
            </div>
        @endif
        <input type="file" name="image" class="form-control" accept="image/*" {{ $banner ? '' : 'required' }}>
        <div class="form-text">Max 2MB. {{ $banner ? 'Leave empty to keep the current image.' : '' }}</div>
        @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Mobile Image</label>
        @if($banner?->mobile_image)
            <div class="mb-2">
                <img src="{{ Storage::url($banner->mobile_image) }}" alt="{{ $banner->title }} mobile"
                     style="width:100px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
            </div>
        @endif
        <input type="file" name="mobile_image" class="form-control" accept="image/*">
        <div class="form-text">Optional. Max 2MB.</div>
        @error('mobile_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Link / URL</label>
        <input type="text" name="link" class="form-control"
               value="{{ old('link', $banner->link ?? '') }}" maxlength="500"
               placeholder="https://… or /products">
        @error('link') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Button Text</label>
        <input type="text" name="button_text" class="form-control"
               value="{{ old('button_text', $banner->button_text ?? '') }}" maxlength="100"
               placeholder="e.g. Shop Now">
        @error('button_text') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Placement *</label>
        <select name="position" class="form-select" required>
            @foreach(['hero' => 'Hero', 'promo' => 'Promo', 'category' => 'Category'] as $value => $label)
                <option value="{{ $value }}"
                    {{ old('position', $banner->position ?? 'hero') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('position') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="form-control"
               value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0">
        @error('sort_order') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check mb-2">
            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>
</div>
