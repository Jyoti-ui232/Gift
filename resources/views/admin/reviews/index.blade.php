@extends('layouts.admin')
@section('title', 'Reviews')
@section('page-title', 'Reviews')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <p class="text-muted mb-0">Moderate customer product reviews.</p>
</div>

<div class="admin-table-card">
    <div class="table-header">
        <h5>
            <i class="fa-solid fa-star me-2" style="color:#7d8531;"></i>
            Reviews <span class="badge bg-secondary ms-1">{{ $reviews->total() }}</span>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>
                            @if($review->product)
                                <a href="{{ route('admin.products.edit', $review->product) }}"
                                   class="text-decoration-none" style="color:#334155;font-weight:600;">
                                    {{ \Illuminate\Support\Str::limit($review->product->name, 28) }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($review->user)
                                <a href="{{ route('admin.customers.show', $review->user) }}"
                                   class="text-decoration-none" style="color:#334155;">
                                    {{ $review->user->name }}
                                </a>
                                <br><small class="text-muted">{{ $review->user->email }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span style="color:#7d8531;font-weight:700;">
                                {{ $review->rating !== null ? $review->rating . '/5' : 'Not rated' }}
                            </span>
                        </td>
                        <td style="max-width:260px;">
                            @if($review->title)
                                <strong style="font-size:13px;">{{ $review->title }}</strong><br>
                            @endif
                            <span class="text-muted small">
                                {{ $review->comment ? \Illuminate\Support\Str::limit($review->comment, 80) : '—' }}
                            </span>
                        </td>
                        <td>{{ optional($review->created_at)->format('d M Y') }}</td>
                        <td>
                            @if($review->is_approved)
                                <span class="badge-status badge-approved">Approved</span>
                            @else
                                <span class="badge-status badge-review">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm {{ $review->is_approved ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                            title="{{ $review->is_approved ? 'Unapprove' : 'Approve' }}">
                                        <i class="fa-solid {{ $review->is_approved ? 'fa-eye-slash' : 'fa-check' }}"></i>
                                        {{ $review->is_approved ? 'Unapprove' : 'Approve' }}
                                    </button>
                                </form>
                                <form method="POST"
                                      action="{{ route('admin.reviews.destroy', $review) }}"
                                      onsubmit="return confirm('Delete this review?')">
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
                            No reviews found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
        <div class="p-3 border-top">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
