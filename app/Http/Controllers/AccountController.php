<?php
namespace App\Http\Controllers;
use App\Models\{Address, Order, Wishlist};
use App\Services\OrderInventoryRestorer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AccountController extends Controller {
    public function __construct() { $this->middleware('auth'); }
    
    public function index() { return redirect()->route('account.profile'); }
    
    public function profile() {
        return view('account.profile', ['user' => auth()->user()]);
    }
    
    public function updateProfile(Request $request) {
        $request->validate(['name' => 'required|string|max:255', 'phone' => 'nullable|string|max:20']);
        auth()->user()->update($request->only(['name', 'phone']));
        return redirect()->route('account.profile')->with('success', 'Profile updated successfully!');
    }
    
    public function orders() {
        $orders = auth()->user()->orders()->with('items')->orderBy('created_at', 'desc')->paginate(10);
        return view('account.orders', compact('orders'));
    }
    
    public function orderDetail(Order $order) {
        if ($order->user_id !== auth()->id()) abort(403);
        $order->load('items.product');
        return view('account.order-detail', compact('order'));
    }

    public function cancelOrder(Order $order) {
        $schemaReady = Schema::hasColumn('orders', 'inventory_restored_at')
            && Schema::hasColumn('orders', 'inventory_restoration_eligible')
            && Schema::hasColumn('order_items', 'product_variant_id');

        abort_unless(
            $schemaReady,
            503,
            'Order cancellation is unavailable until the inventory restoration migration has been applied.'
        );

        DB::transaction(function () use ($order) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            abort_unless((int) $lockedOrder->user_id === (int) auth()->id(), 403);
            abort_unless(
                $lockedOrder->status === 'pending',
                409,
                'Only pending orders can be cancelled.'
            );

            app(OrderInventoryRestorer::class)->restore($lockedOrder);
            $lockedOrder->status = 'cancelled';
            $lockedOrder->save();
        });

        return redirect()
            ->route('account.orders.detail', $order)
            ->with('success', 'Order cancelled successfully.');
    }
    
    public function addresses() {
        $addresses = auth()->user()->addresses()->get();
        return view('account.addresses', compact('addresses'));
    }
    
    public function storeAddress(Request $request) {
        $validated = $request->validate($this->addressValidationRules());
        if ($validated['is_default'] ?? false) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }
        auth()->user()->addresses()->create($validated);
        return redirect()->route('account.addresses')->with('success', 'Address added!');
    }
    
    public function updateAddress(Request $request, Address $address) {
        if ($address->user_id !== auth()->id()) abort(403);
        $validated = $request->validate($this->addressValidationRules());
        $validated['is_default'] = $validated['is_default'] ?? false;

        if ($validated['is_default']) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }
        $address->update($validated);
        return redirect()->route('account.addresses')->with('success', 'Address updated!');
    }

    private function addressValidationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'type' => 'nullable|string|in:home,office,other',
            'is_default' => 'nullable|boolean',
        ];
    }
    
    public function destroyAddress(Address $address) {
        if ($address->user_id !== auth()->id()) abort(403);
        $address->delete();
        return redirect()->route('account.addresses')->with('success', 'Address deleted!');
    }
    
    public function wishlist() {
        $wishlistItems = auth()->user()->wishlist()->with('product.images')->get();
        return view('account.wishlist', compact('wishlistItems'));
    }
}
