@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h2>Add Occasion</h2>
        <p class="text-muted">Add a new occasion to GiftBloom.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.occasions.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Occasion Name *</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           placeholder="e.g. Birthday"
                           required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="3"
                              placeholder="Enter occasion description">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Occasion Image</label>
                    <input type="file"
                           name="image"
                           class="form-control"
                           accept="image/*">
                    @error('image')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number"
                           name="sort_order"
                           class="form-control"
                           value="{{ old('sort_order', 0) }}"
                           min="0">
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input"
                           id="is_active"
                           checked>
                    <label class="form-check-label" for="is_active">
                        Active
                    </label>
                </div>

                <button type="submit" class="btn btn-success">
                    Save Occasion
                </button>

                <a href="{{ route('admin.occasions.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>
</div>
@endsection