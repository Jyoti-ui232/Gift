
@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Manage Occasions</h2>
            <p class="text-muted mb-0">Add and manage GiftBloom occasions.</p>
        </div>

        <a href="{{ route('admin.occasions.create') }}"
           class="btn btn-success">
            + Add Occasion
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($occasions as $occasion)
                        <tr>
                            <td>
                                @if($occasion->image)
                                    <img
                                        src="{{ asset('storage/' . $occasion->image) }}"
                                        alt="{{ $occasion->name }}"
                                        width="60"
                                        height="50"
                                        style="object-fit: cover; border-radius: 6px;"
                                    >
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>

                            <td>{{ $occasion->name }}</td>
                            <td>{{ $occasion->slug }}</td>
                            <td>{{ $occasion->products_count }}</td>

                            <td>
                                @if($occasion->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.occasions.edit', $occasion) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.occasions.destroy', $occasion) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this occasion?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                No occasions found. Click Add Occasion to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $occasions->links() }}
    </div>
</div>
@endsection
