@extends('layouts.admin')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="admin-table-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="fa-solid fa-pen-to-square me-2" style="color:#7d8531;"></i>
            Edit Category
        </h5>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Categories
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

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $category->name) }}" required maxlength="255">
            </div>

            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control"
                       value="{{ old('slug', $category->slug) }}" maxlength="255">
                <div class="form-text">Leave as-is to keep the current URL slug.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Parent Category</label>
                <select name="parent_id" class="form-select">
                    <option value="">None (top-level)</option>
                    @foreach($categories as $parent)
                        <option value="{{ $parent->id }}"
                            {{ (string) old('parent_id', $category->parent_id) === (string) $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Icon (CSS class / emoji)</label>
                <input type="text" name="icon" class="form-control"
                       value="{{ old('icon', $category->icon) }}" maxlength="255">
            </div>

            <div class="col-md-6">
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-control"
                       value="{{ old('sort_order', $category->sort_order) }}" min="0">
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">Meta Title</label>
                <input type="text" name="meta_title" class="form-control"
                       value="{{ old('meta_title', $category->meta_title) }}" maxlength="255">
            </div>

            <div class="col-md-6">
                <label class="form-label">Meta Description</label>
                <input type="text" name="meta_description" class="form-control"
                       value="{{ old('meta_description', $category->meta_description) }}" maxlength="255">
            </div>

            <div class="col-md-6">
                <label class="form-label">Image</label>
                @if($category->image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                             style="width:72px;height:72px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="col-md-6 d-flex align-items-end gap-4 flex-wrap">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="show_in_nav" id="show_in_nav" value="1"
                           {{ old('show_in_nav', $category->show_in_nav) ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_in_nav">Show in navigation</label>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2 flex-wrap">
            <button type="submit" class="btn-brand">
                <i class="fa-solid fa-floppy-disk"></i> Update Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
