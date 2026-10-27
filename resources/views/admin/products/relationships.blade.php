@php
    $selectedOccasions = collect(old(
        'occasions',
        isset($product) ? $product->occasions->pluck('id')->all() : []
    ))->map(fn ($id) => (int) $id)->all();

    $selectedAdditionalCategories = collect(old(
        'additional_categories',
        isset($product) ? $product->additionalCategories->pluck('id')->all() : []
    ))->map(fn ($id) => (int) $id)->all();

    $variantRows = old('variants');
    if ($variantRows === null) {
        $variantRows = isset($product)
            ? $product->variants->map(fn ($variant) => $variant->only([
                'id', 'name', 'type', 'price', 'sale_price', 'stock', 'sku', 'is_default',
            ]))->all()
            : [];
    }
    if (empty($variantRows)) {
        $variantRows = [[]];
    }
@endphp

<hr class="my-4">

<h6 class="mb-2">Additional Categories</h6>
<p class="text-muted small mb-3">Keep the primary category above; select any other categories this product belongs to.</p>
<input type="hidden" name="additional_categories_present" value="1">
<div class="row g-2">
    @forelse($additionalCategories as $category)
        <div class="col-sm-6 col-lg-4">
            <label class="form-check">
                <input class="form-check-input" type="checkbox" name="additional_categories[]"
                       value="{{ $category->id }}" data-additional-category
                       @checked(in_array($category->id, $selectedAdditionalCategories, true))>
                <span class="form-check-label">{{ $category->name }}</span>
            </label>
        </div>
    @empty
        <p class="text-muted mb-0">No categories available.</p>
    @endforelse
</div>

<hr class="my-4">

<h6 class="mb-3">Occasions</h6>
<input type="hidden" name="occasions_present" value="1">
<div class="row g-2">
    @forelse($occasions as $occasion)
        <div class="col-sm-6 col-lg-4">
            <label class="form-check">
                <input class="form-check-input" type="checkbox" name="occasions[]"
                       value="{{ $occasion->id }}"
                       @checked(in_array($occasion->id, $selectedOccasions, true))>
                <span class="form-check-label">{{ $occasion->name }}</span>
            </label>
        </div>
    @empty
        <p class="text-muted mb-0">No occasions available.</p>
    @endforelse
</div>

<hr class="my-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0">Product Variants</h6>
    <button type="button" class="btn btn-outline-secondary btn-sm" data-add-variant>
        Add Variant
    </button>
</div>

<div data-variant-rows data-next-index="{{ count($variantRows) }}">
    @foreach($variantRows as $index => $variant)
        <div class="row g-2 align-items-end mb-3" data-variant-row>
            @if(!empty($variant['id']))
                <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] }}">
            @endif
            <div class="col-md-3">
                <label class="form-label">Name</label>
                <input type="text" name="variants[{{ $index }}][name]" class="form-control"
                       value="{{ $variant['name'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <input type="text" name="variants[{{ $index }}][type]" class="form-control"
                       value="{{ $variant['type'] ?? '' }}" placeholder="size, weight, flavor">
            </div>
            <div class="col-md-2">
                <label class="form-label">Price (₹)</label>
                <input type="number" name="variants[{{ $index }}][price]" class="form-control"
                       min="0" step="0.01" value="{{ $variant['price'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Sale Price (₹)</label>
                <input type="number" name="variants[{{ $index }}][sale_price]" class="form-control"
                       min="0" step="0.01" value="{{ $variant['sale_price'] ?? '' }}">
            </div>
            <div class="col-md-1">
                <label class="form-label">Stock</label>
                <input type="number" name="variants[{{ $index }}][stock]" class="form-control"
                       min="0" value="{{ $variant['stock'] ?? '' }}">
            </div>
            <div class="col-md-1">
                <label class="form-label">SKU</label>
                <input type="text" name="variants[{{ $index }}][sku]" class="form-control"
                       value="{{ $variant['sku'] ?? '' }}">
            </div>
            <div class="col-md-1">
                <label class="form-check-label d-block">Default</label>
                <input type="checkbox" name="variants[{{ $index }}][is_default]" value="1"
                       class="form-check-input"
                       @checked((bool) ($variant['is_default'] ?? false))>
            </div>
        </div>
    @endforeach
</div>

<template data-variant-template>
    <div class="row g-2 align-items-end mb-3" data-variant-row>
        <div class="col-md-3">
            <label class="form-label">Name</label>
            <input type="text" name="variants[__INDEX__][name]" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">Type</label>
            <input type="text" name="variants[__INDEX__][type]" class="form-control"
                   placeholder="size, weight, flavor">
        </div>
        <div class="col-md-2">
            <label class="form-label">Price (₹)</label>
            <input type="number" name="variants[__INDEX__][price]" class="form-control"
                   min="0" step="0.01">
        </div>
        <div class="col-md-2">
            <label class="form-label">Sale Price (₹)</label>
            <input type="number" name="variants[__INDEX__][sale_price]" class="form-control"
                   min="0" step="0.01">
        </div>
        <div class="col-md-1">
            <label class="form-label">Stock</label>
            <input type="number" name="variants[__INDEX__][stock]" class="form-control" min="0">
        </div>
        <div class="col-md-1">
            <label class="form-label">SKU</label>
            <input type="text" name="variants[__INDEX__][sku]" class="form-control">
        </div>
        <div class="col-md-1">
            <label class="form-check-label d-block">Default</label>
            <input type="checkbox" name="variants[__INDEX__][is_default]" value="1"
                   class="form-check-input">
        </div>
    </div>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const primaryCategory = document.querySelector('select[name="category_id"]');
    const additionalCategoryInputs = document.querySelectorAll('[data-additional-category]');

    if (primaryCategory) {
        const updateAdditionalCategoryOptions = function () {
            additionalCategoryInputs.forEach(function (input) {
                const isPrimary = input.value === primaryCategory.value;
                input.disabled = isPrimary;
                if (isPrimary) input.checked = false;
            });
        };

        primaryCategory.addEventListener('change', updateAdditionalCategoryOptions);
        updateAdditionalCategoryOptions();
    }

    const rows = document.querySelector('[data-variant-rows]');
    const template = document.querySelector('[data-variant-template]');
    const addButton = document.querySelector('[data-add-variant]');

    if (!rows || !template || !addButton) return;

    addButton.addEventListener('click', function () {
        const index = Number(rows.dataset.nextIndex);
        const markup = template.innerHTML.replaceAll('__INDEX__', String(index));
        rows.insertAdjacentHTML('beforeend', markup);
        rows.dataset.nextIndex = String(index + 1);
    });
});
</script>
@endpush
