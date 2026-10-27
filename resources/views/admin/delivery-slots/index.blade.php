@extends('layouts.admin')
@section('title', 'Delivery Slots')
@section('page-title', 'Delivery Slots')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <p class="text-muted mb-0">Manage checkout delivery time slots and charges.</p>
    <a href="{{ route('admin.delivery-slots.create') }}" class="btn-brand">
        <i class="fa-solid fa-plus"></i> Add Slot
    </a>
</div>

<div class="admin-table-card">
    <div class="table-header">
        <h5>
            <i class="fa-solid fa-truck-fast me-2" style="color:#7d8531;"></i>
            Delivery Slots <span class="badge bg-secondary ms-1">{{ $slots->count() }}</span>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Time Range</th>
                    <th>Type</th>
                    <th>Extra Charge</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($slots as $slot)
                    <tr>
                        <td><strong style="font-size:13.5px;">{{ $slot->name }}</strong></td>
                        <td>{{ $slot->time_range }}</td>
                        <td>{{ ucfirst($slot->type) }}</td>
                        <td>
                            @if((float) $slot->extra_charge > 0)
                                ₹{{ number_format($slot->extra_charge, 2) }}
                            @else
                                <span class="text-muted">Free</span>
                            @endif
                        </td>
                        <td>{{ $slot->sort_order }}</td>
                        <td>
                            @if($slot->is_active)
                                <span class="badge-status badge-active">Active</span>
                            @else
                                <span class="badge-status badge-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.delivery-slots.edit', $slot) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.delivery-slots.destroy', $slot) }}"
                                      onsubmit="return confirm('Delete this delivery slot?')">
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
                            No delivery slots found. Click Add Slot to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
