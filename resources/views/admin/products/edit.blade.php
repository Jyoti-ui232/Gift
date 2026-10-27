
@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')

<div class="admin-table-card p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="fa-solid fa-pen-to-square me-2"
               style="color:#7d8531;"></i>
            Edit Product
        </h5>

        <a href="{{ route('admin.products.index') }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back to Products
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

    <form action="{{ route('admin.products.update', $product) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <h6 class="mb-3">Basic Information</h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Product Name *</label>
                <input type="text" name="name"
                       class="form-control"
                       value="{{ old('name', $product->name) }}"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label">SKU *</label>
                <input type="text" name="sku"
                       class="form-control"
                       value="{{ old('sku', $product->sku) }}"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Price (₹) *</label>
                <input type="number" name="price"
                       class="form-control"
                       min="0" step="0.01"
                       value="{{ old('price', $product->price) }}"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Sale Price (₹)</label>
                <input type="number" name="sale_price"
                       class="form-control"
                       min="0" step="0.01"
                       value="{{ old('sale_price', $product->sale_price) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Stock</label>
                <input type="number" name="stock"
                       class="form-control"
                       min="0"
                       value="{{ old('stock', $product->stock ?? 0) }}">
            </div>

            <div class="col-12">
                <label class="form-label">Short Description</label>
                <textarea name="short_description"
                          class="form-control"
                          rows="3">{{ old('short_description', $product->short_description) }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Full Description</label>
                <textarea name="description"
                          class="form-control"
                          rows="5">{{ old('description', $product->description) }}</textarea>
            </div>

        </div>

        <hr class="my-4">

        <h6 class="mb-3">Product Images</h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Current Thumbnail</label>

                @if($product->thumbnail)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                             alt="{{ $product->name }}"
                             style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                    </div>
                @else
                    <p class="text-muted">No thumbnail uploaded.</p>
                @endif

                <label class="form-label">Replace Thumbnail</label>
                <input type="file" name="thumbnail"
                       class="form-control" accept="image/*">
                <small class="text-muted">
                    Nayi image select karogi tabhi thumbnail replace hoga.
                </small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Add More Images</label>
                <input type="file" name="images[]"
                       class="form-control"
                       accept="image/*" multiple>
                <small class="text-muted">
                    Existing images delete nahi hongi; selected images add hongi.
                </small>
            </div>

        </div>

        @if($product->images->count())
            <div class="mt-4">
                <label class="form-label">Additional Images Already Saved</label>

                <div class="d-flex flex-wrap gap-3 mt-2">
                    @foreach($product->images as $image)
                        <div class="text-center">
                            <img src="{{ $image->url }}"
                                 alt="{{ $product->name }}"
                                 style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @include('admin.products.relationships')

        <hr class="my-4">

        <h6 class="mb-3">Product Settings</h6>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1"
                        {{ (string) old('is_active', (int) $product->is_active) === '1' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="0"
                        {{ (string) old('is_active', (int) $product->is_active) === '0' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-brand">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Update Product
            </button>

            <a href="{{ route('admin.products.index') }}"
               class="btn btn-outline-secondary">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
