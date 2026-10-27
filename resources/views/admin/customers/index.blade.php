@extends('layouts.admin')
@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex gap-2 flex-wrap">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search name or email…"
               class="form-control form-control-sm"
               style="width:220px;">
        <button type="submit" class="btn btn-sm btn-secondary">
            <i class="fa-solid fa-magnifying-glass"></i> Search
        </button>
        @if(request()->filled('search'))
            <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
        @endif
    </form>
</div>

<div class="admin-table-card">
    <div class="table-header">
        <h5>
            <i class="fa-solid fa-users me-2" style="color:#7d8531;"></i>
            Customers <span class="badge bg-secondary ms-1">{{ $customers->total() }}</span>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Orders</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td><strong style="font-size:13.5px;">{{ $customer->name }}</strong></td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?: '—' }}</td>
                        <td>{{ $customer->orders_count }}</td>
                        <td>{{ optional($customer->created_at)->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.customers.show', $customer) }}"
                               class="btn btn-sm btn-outline-primary" style="font-size:11px;">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            No customers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
        <div class="p-3 border-top">
            {{ $customers->links() }}
        </div>
    @endif
</div>
@endsection
