@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h2>Edit Occasion</h2>
        <p class="text-muted">Update this GiftBloom occasion.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('admin.occasions.update', $occasion) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Occasion Name *</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $occasion->name) }}"
                           required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="3">{{ old('description', $occasion->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    @if($occasion->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $occasion->image) }}"
                                 alt="{{ $occasion->name }}"
                                 width="120"
                                 style="border-radius: 8px;">
                        </div>
                    @else
                        <p class="text-muted">No image uploaded.</p>
                    @endif

                    <label class="form-label">Choose New Image (optional)</label>
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
                           value="{{ old('sort_order', $occasion->sort_order ?? 0) }}"
                           min="0">
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input"
                           id="is_active"
                           {{ old('is_active', $occasion->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active
                    </label>
                </div>

                <button type="submit" class="btn btn-success">
                    Update Occasion
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
