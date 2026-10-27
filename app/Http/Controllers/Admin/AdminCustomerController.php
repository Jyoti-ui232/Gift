<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller {
    public function index(Request $request) {
        $query = User::where('is_admin', false)->withCount('orders');
        if ($request->search) $query->where(function($q) use ($request) { $q->where('name', 'like', '%'.$request->search.'%')->orWhere('email', 'like', '%'.$request->search.'%'); });
        $customers = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }
    public function show(User $user) {
        $user->load(['orders.items', 'addresses']);
        return view('admin.customers.show', compact('user'));
    }
}
