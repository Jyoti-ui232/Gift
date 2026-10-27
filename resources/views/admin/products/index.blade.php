@extends('layouts.admin')
@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<!-- Filters & Header -->
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products…"
               class="form-control form-control-sm" style="width:200px;">
        <select name="category_id" class="form-select form-select-sm" style="width:160px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <select name="status" class="form-select form-select-sm" style="width:130px;">
            <option value="">All Status</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="btn btn-sm btn-secondary">
            <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>
        @if(request()->hasAny(['search','category_id','status']))
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        @endif
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn-brand">
        <i class="fa-solid fa-plus"></i> Add Product
    </a>
</div>

<!-- Products Table -->
<div class="admin-table-card">
    <div class="table-header">
        <h5><i class="fa-solid fa-box-open me-2" style="color:#7d8531;"></i>
            Products <span class="badge bg-secondary ms-1">{{ $products->total() }}</span>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:60px;">Image</th>
                    <th>Name / SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Sale Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->thumbnail)
                            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}"
                                 style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                        @else
                            <div style="width:48px;height:48px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong style="font-size:13.5px;">{{ $product->name }}</strong><br>
                        <small class="text-muted">SKU: {{ $product->sku }}</small>
                    </td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td>₹{{ number_format($product->price, 2) }}</td>
                    <td>
                        @if($product->sale_price)
                            <span style="color:#16a34a;font-weight:600;">₹{{ number_format($product->sale_price, 2) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($product->stock <= 0)
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif($product->stock <= 5)
                            <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                        @else
                            <span class="text-dark">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input toggle-active" type="checkbox"
                                   data-id="{{ $product->id }}"
                                   data-url="{{ route('admin.products.toggle-active', $product) }}"
                                   {{ $product->is_active ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
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
                        <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i>
                        No products found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="p-3 border-top">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-active').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        const url = this.dataset.url;
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            // Toggle updated
        })
        .catch(err => console.error(err));
    });
});
</script>
@endpush
