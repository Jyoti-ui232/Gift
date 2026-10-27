@extends('layouts.admin')
@section('title', 'Coupons')
@section('page-title', 'Coupons')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <p class="text-muted mb-0">Manage discount coupons for checkout.</p>
    <a href="{{ route('admin.coupons.create') }}" class="btn-brand">
        <i class="fa-solid fa-plus"></i> Add Coupon
    </a>
</div>

<div class="admin-table-card">
    <div class="table-header">
        <h5>
            <i class="fa-solid fa-ticket me-2" style="color:#7d8531;"></i>
            Coupons <span class="badge bg-secondary ms-1">{{ $coupons->total() }}</span>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Min Order</th>
                    <th>Usage</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td><code style="font-size:12px;">{{ $coupon->code }}</code></td>
                        <td>
                            @if($coupon->type === 'percentage')
                                {{ rtrim(rtrim(number_format($coupon->value, 2), '0'), '.') }}%
                            @else
                                ₹{{ number_format($coupon->value, 2) }}
                            @endif
                        </td>
                        <td>₹{{ number_format($coupon->min_order_amount, 2) }}</td>
                        <td>
                            {{ $coupon->used_count }}
                            @if($coupon->usage_limit)
                                / {{ $coupon->usage_limit }}
                            @else
                                <span class="text-muted">/ ∞</span>
                            @endif
                        </td>
                        <td>
                            {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : '—' }}
                        </td>
                        <td>
                            @if($coupon->is_active)
                                <span class="badge-status badge-active">Active</span>
                            @else
                                <span class="badge-status badge-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.coupons.destroy', $coupon) }}"
                                      onsubmit="return confirm('Delete this coupon?')">
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
                            No coupons found. Click Add Coupon to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($coupons->hasPages())
        <div class="p-3 border-top">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection
