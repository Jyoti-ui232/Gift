@extends('layouts.admin')
@section('title', 'Banners')
@section('page-title', 'Banners')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <p class="text-muted mb-0">Manage homepage and promo banners.</p>
    <a href="{{ route('admin.banners.create') }}" class="btn-brand">
        <i class="fa-solid fa-plus"></i> Add Banner
    </a>
</div>

<div class="admin-table-card">
    <div class="table-header">
        <h5>
            <i class="fa-solid fa-image me-2" style="color:#7d8531;"></i>
            Banners <span class="badge bg-secondary ms-1">{{ $banners->total() }}</span>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:90px;">Image</th>
                    <th>Title</th>
                    <th>Position</th>
                    <th>Link</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>
                            @if($banner->image)
                                <img src="{{ Storage::url($banner->image) }}"
                                     alt="{{ $banner->title }}"
                                     style="width:72px;height:44px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <strong style="font-size:13.5px;">{{ $banner->title }}</strong>
                            @if($banner->subtitle)
                                <br><small class="text-muted">{{ $banner->subtitle }}</small>
                            @endif
                        </td>
                        <td>{{ ucfirst($banner->position) }}</td>
                        <td>
                            @if($banner->link)
                                <a href="{{ $banner->link }}" target="_blank" rel="noopener noreferrer"
                                   class="small text-decoration-none" style="color:#7d8531;">
                                    {{ \Illuminate\Support\Str::limit($banner->link, 28) }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $banner->sort_order }}</td>
                        <td>
                            @if($banner->is_active)
                                <span class="badge-status badge-active">Active</span>
                            @else
                                <span class="badge-status badge-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.banners.edit', $banner) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.banners.destroy', $banner) }}"
                                      onsubmit="return confirm('Delete this banner?')">
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
                        <td colspan="7" class="text-center text-muted py-5">
                            No banners found. Click Add Banner to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($banners->hasPages())
        <div class="p-3 border-top">
            {{ $banners->links() }}
        </div>
    @endif
</div>
@endsection
