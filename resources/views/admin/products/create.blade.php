@extends('layouts.admin')

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')

<div class="admin-table-card p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <i class="fa-solid fa-box-open me-2"
               style="color:#7d8531;"></i>
            Add New Product
        </h5>

        <a href="{{ route('admin.products.index') }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back to Products
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        {{-- Basic Information --}}
        <h6 class="mb-3">Basic Information</h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Product Name *</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name') }}"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label">SKU *</label>
                <input type="text"
                       name="sku"
                       class="form-control"
                       value="{{ old('sku') }}"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Category *</label>
                <select name="category_id"
                        class="form-select"
                        required>
                    <option value="">Select Category</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Price (₹) *</label>
                <input type="number"
                       name="price"
                       class="form-control"
                       value="{{ old('price') }}"
                       min="0"
                       step="0.01"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Sale Price (₹)</label>
                <input type="number"
                       name="sale_price"
                       class="form-control"
                       value="{{ old('sale_price') }}"
                       min="0"
                       step="0.01">
            </div>

            <div class="col-md-6">
                <label class="form-label">Stock *</label>
                <input type="number"
                       name="stock"
                       class="form-control"
                       value="{{ old('stock', 0) }}"
                       min="0"
                       required>
            </div>

            <div class="col-12">
                <label class="form-label">Short Description</label>
                <textarea name="short_description"
                          class="form-control"
                          rows="3">{{ old('short_description') }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Full Description</label>
                <textarea name="description"
                          class="form-control"
                          rows="5">{{ old('description') }}</textarea>
            </div>

        </div>

        <hr class="my-4">

        {{-- Product Images --}}
        <h6 class="mb-3">Product Images</h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Main Thumbnail *</label>
                <input type="file"
                       name="thumbnail"
                       class="form-control"
                       accept="image/*"
                       required>
                <small class="text-muted">
                    Main product image select karo.
                </small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Additional Images</label>
                <input type="file"
                       name="images[]"
                       class="form-control"
                       accept="image/*"
                       multiple>
                <small class="text-muted">
                    Ek ya zyada extra images select kar sakti ho.
                </small>
            </div>

        </div>

        @include('admin.products.relationships')

        <hr class="my-4">

        {{-- Product Settings --}}
        <h6 class="mb-3">Product Settings</h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Product Status</label>
                <select name="is_active" class="form-select">
                    <option value="1"
                        {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0"
                        {{ old('is_active') === '0' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

        </div>

        {{-- Buttons --}}
        <div class="d-flex gap-2 mt-4">

            <button type="submit" class="btn-brand">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Save Product
            </button>

            <a href="{{ route('admin.products.index') }}"
               class="btn btn-outline-secondary">
                Cancel
            </a>

        </div>

    </form>

</div>

@endsection
