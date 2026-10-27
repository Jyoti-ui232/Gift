<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Order, Product, User, Category};

class AdminDashboardController extends Controller {
    public function index() {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'total_products' => Product::count(),
            'total_customers' => User::where('is_admin', false)->count(),
            'total_categories' => Category::count(),
        ];
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->limit(10)->get();
        $topProducts = Product::withCount('wishlists')->orderBy('wishlists_count', 'desc')->limit(5)->get();
        $monthlySales = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total) as total, COUNT(*) as count')->where('created_at', '>=', now()->subMonths(6))->groupByRaw('MONTH(created_at), YEAR(created_at)')->orderByRaw('YEAR(created_at), MONTH(created_at)')->get();
        return view('admin.dashboard', compact('stats','recentOrders','topProducts','monthlySales'));
    }
}
