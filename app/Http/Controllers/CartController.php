<?php
namespace App\Http\Controllers;
use App\Models\{CartItem, Product, ProductVariant, Coupon, AddOn};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CartController extends Controller {
    private function getSessionId() { return session()->getId(); }
    
    public function index() {
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum('total');
        $discount = 0;
        $coupon = null;
        if (session('coupon_code')) {
            $coupon = Coupon::where('code', session('coupon_code'))->first();
            if ($coupon && $coupon->isValid(auth()->user())) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }
        $total = $subtotal - $discount;
        return view('cart.index', compact('cartItems','subtotal','discount','coupon','total'));
    }
    
    public function add(Request $request) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:10',
            'variant_id' => [
                'nullable',
                Rule::exists('product_variants', 'id')->where('product_id', $request->input('product_id')),
            ],
            'add_ons' => 'nullable|array',
            'add_ons.*' => 'integer|exists:add_ons,id',
            'gift_message' => 'nullable|string|max:500',
        ]);
        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active || (int) $product->stock <= 0) {
            return redirect()->back()->with('error', 'This product is currently unavailable.');
        }

        $variantId = $request->variant_id;
        $giftMessage = $request->filled('gift_message')
            ? trim($request->gift_message)
            : null;
        $addOnIds = collect($request->input('add_ons', []))->filter()->values();
        $addOns = $addOnIds->isEmpty()
            ? null
            : AddOn::whereIn('id', $addOnIds)->where('is_active', true)->get()
                ->map(fn ($addon) => [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => (float) $addon->price,
                ])->values()->all();

        $query = CartItem::where(auth()->check() ? 'user_id' : 'session_id', auth()->check() ? auth()->id() : $this->getSessionId())
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId);
        $cartItem = $query->get()->first(function ($item) use ($addOns, $giftMessage) {
            $existingAddOnIds = collect($item->add_ons ?? [])
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values()
                ->all();
            $requestedAddOnIds = collect($addOns ?? [])
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            return $existingAddOnIds === $requestedAddOnIds
                && $item->gift_message === $giftMessage;
        });

        if ($cartItem) {
            $quantityToAdd = (int) ($request->quantity ?? 1);
            if ($cartItem->quantity + $quantityToAdd > 10) {
                throw ValidationException::withMessages([
                    'quantity' => 'The total quantity for this cart item may not exceed 10.',
                ]);
            }

            $cartItem->increment('quantity', $quantityToAdd);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'session_id' => !auth()->check() ? $this->getSessionId() : null,
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'quantity' => $request->quantity ?? 1,
                'add_ons' => $addOns,
                'gift_message' => $giftMessage,
            ]);
        }
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Added to cart!', 'cart_count' => $this->getCartCount()]);
        }
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }
    
    public function update(Request $request, CartItem $item) {
        $this->ensureCartItemOwnership($item);
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);
        $item->update(['quantity' => $request->quantity]);
        if ($request->ajax()) {
            $cartItems = $this->getCartItems();
            $subtotal = $cartItems->sum('total');
            return response()->json(['success' => true, 'subtotal' => $subtotal, 'item_total' => $item->total]);
        }
        return redirect()->route('cart.index');
    }
    
    public function remove(Request $request, CartItem $item) {
        $this->ensureCartItemOwnership($item);
        $item->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'cart_count' => $this->getCartCount()]);
        }
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    private function ensureCartItemOwnership(CartItem $item): void {
        $query = CartItem::whereKey($item->getKey());

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        abort_unless($query->exists(), 404);
    }
    
    public function applyCoupon(Request $request) {
        $request->validate(['code' => 'required|string']);
        $coupon = Coupon::where('code', strtoupper($request->code))->first();
        if (!$coupon || !$coupon->isValid(auth()->user())) {
            return redirect()->route('cart.index')->with('error', 'Invalid or expired coupon code.');
        }
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum('total');
        if ($subtotal < $coupon->min_order_amount) {
            return redirect()->route('cart.index')->with('error', 'Minimum order amount of ₹'.$coupon->min_order_amount.' required.');
        }
        session(['coupon_code' => $coupon->code]);
        return redirect()->route('cart.index')->with('success', 'Coupon applied successfully!');
    }
    
    public function removeCoupon() {
        session()->forget('coupon_code');
        return redirect()->route('cart.index')->with('success', 'Coupon removed.');
    }
    
    public function getCartItems() {
        $query = CartItem::with(['product.images', 'variant']);
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', $this->getSessionId());
        }
        return $query->get();
    }
    
    public function getCartCount() {
        $query = CartItem::query();
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', $this->getSessionId());
        }
        return $query->sum('quantity');
    }
}
