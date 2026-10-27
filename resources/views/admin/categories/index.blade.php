@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <p class="text-muted mb-0">Manage gift categories and subcategories.</p>
    <a href="{{ route('admin.categories.create') }}" class="btn-brand">
        <i class="fa-solid fa-plus"></i> Add Category
    </a>
</div>

<div class="admin-table-card">
    <div class="table-header">
        <h5>
            <i class="fa-solid fa-tags me-2" style="color:#7d8531;"></i>
            Categories <span class="badge bg-secondary ms-1">{{ $categories->total() }}</span>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px;">Image</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Parent</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Nav</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}"
                                     alt="{{ $category->name }}"
                                     style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                            @else
                                <div style="width:48px;height:48px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong style="font-size:13.5px;">{{ $category->name }}</strong>
                            @if($category->sort_order)
                                <br><small class="text-muted">Order: {{ $category->sort_order }}</small>
                            @endif
                        </td>
                        <td><code style="font-size:12px;">{{ $category->slug }}</code></td>
                        <td>{{ $category->parent->name ?? '—' }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge-status badge-active">Active</span>
                            @else
                                <span class="badge-status badge-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($category->show_in_nav)
                                <span class="badge bg-success-subtle text-success border">Yes</span>
                            @else
                                <span class="text-muted">No</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            No categories found. Click Add Category to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
        <div class="p-3 border-top">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
