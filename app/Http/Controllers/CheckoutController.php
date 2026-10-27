<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderItem, CartItem, Coupon, DeliverySlot};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public const FREE_DELIVERY_MIN = 999;
    public const STANDARD_DELIVERY_CHARGE = 49;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->claimSessionCartForUser();

        $cartItems = $this->validatedCartItems();
        if ($cartItems instanceof \Illuminate\Http\RedirectResponse) {
            return $cartItems;
        }

        [$subtotal, $discount, $coupon, $deliveryCharge, $total] = $this->calculateTotals($cartItems);

        $deliverySlots = DeliverySlot::where('is_active', true)->orderBy('sort_order')->get();
        $addresses = auth()->user()->addresses()->latest()->get();
        $defaultAddress = auth()->user()->defaultAddress;
        $cartGiftMessage = $cartItems
            ->pluck('gift_message')
            ->filter(fn ($message) => filled($message))
            ->unique()
            ->implode("\n\n");

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'discount',
            'coupon',
            'deliverySlots',
            'addresses',
            'defaultAddress',
            'deliveryCharge',
            'total',
            'cartGiftMessage'
        ));
    }

    public function store(Request $request)
    {
        $this->claimSessionCartForUser();

        $deliverySlotsExist = DeliverySlot::where('is_active', true)->exists();

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => ['required', 'string', 'regex:/^[6-9]\d{9}$/'],
            'recipient_address' => 'required|string|min:5|max:500',
            'recipient_city' => 'required|string|max:100',
            'recipient_state' => 'required|string|max:100',
            'recipient_pincode' => ['required', 'string', 'regex:/^\d{6}$/'],
            'delivery_date' => 'required|date|after_or_equal:today',
            'delivery_slot_id' => [
                $deliverySlotsExist ? 'required' : 'nullable',
                'integer',
                Rule::exists('delivery_slots', 'id')->where('is_active', true),
            ],
            'payment_method' => 'required|in:cod',
            'gift_message' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string|max:500',
            'save_address' => 'nullable|boolean',
        ], [
            'recipient_name.required' => 'Please enter the recipient name.',
            'recipient_phone.required' => 'Please enter a phone number.',
            'recipient_phone.regex' => 'Enter a valid 10-digit Indian mobile number.',
            'recipient_address.required' => 'Please enter the delivery address.',
            'recipient_city.required' => 'Please enter the city.',
            'recipient_state.required' => 'Please enter the state.',
            'recipient_pincode.required' => 'Please enter the pincode.',
            'recipient_pincode.regex' => 'Pincode must be a 6-digit number.',
            'delivery_date.required' => 'Please choose a delivery date.',
            'delivery_date.after_or_equal' => 'Delivery date cannot be in the past.',
            'delivery_slot_id.required' => 'Please select a delivery slot.',
            'payment_method.in' => 'Only Cash on Delivery is available right now.',
        ]);

        DB::beginTransaction();
        try {
            if (session('coupon_code')) {
                $lockedCoupon = Coupon::where('code', session('coupon_code'))
                    ->lockForUpdate()
                    ->first();

                if (
                    $lockedCoupon
                    && $lockedCoupon->isValid()
                    && $lockedCoupon->hasReachedUserLimit(auth()->user())
                ) {
                    DB::rollBack();
                    session()->forget('coupon_code');

                    return redirect()
                        ->route('cart.index')
                        ->with('error', 'You have reached the usage limit for this coupon.');
                }
            }

            $cartItems = $this->validatedCartItems(lockStock: true);
            if ($cartItems instanceof \Illuminate\Http\RedirectResponse) {
                DB::rollBack();

                return $cartItems;
            }

            $slot = !empty($validated['delivery_slot_id'])
                ? DeliverySlot::where('is_active', true)->find($validated['delivery_slot_id'])
                : null;

            [$subtotal, $discount, $coupon, $deliveryCharge, $total] = $this->calculateTotals($cartItems, $slot);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'status' => 'pending',
                'inventory_restoration_eligible' => true,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'delivery_charge' => $deliveryCharge,
                'total' => $total,
                'coupon_code' => $coupon?->code,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'recipient_address' => $validated['recipient_address'],
                'recipient_city' => $validated['recipient_city'],
                'recipient_state' => $validated['recipient_state'],
                'recipient_pincode' => $validated['recipient_pincode'],
                'delivery_type' => $slot?->type ?? 'standard',
                'delivery_date' => $validated['delivery_date'],
                'delivery_slot' => $slot?->time_range,
                'gift_message' => $validated['gift_message'] ?? null,
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant?->name,
                    'price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                    'add_ons' => $item->add_ons,
                ]);
            }

            foreach ($cartItems->groupBy('product_id') as $productItems) {
                $productItems->first()->product->decrement(
                    'stock',
                    $productItems->sum('quantity')
                );
            }

            foreach ($cartItems->filter(fn ($item) => $item->variant_id !== null)->groupBy('variant_id') as $variantItems) {
                $variantItems->first()->variant->decrement(
                    'stock',
                    $variantItems->sum('quantity')
                );
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            if ($request->boolean('save_address')) {
                auth()->user()->addresses()->create([
                    'name' => $validated['recipient_name'],
                    'phone' => $validated['recipient_phone'],
                    'address_line1' => $validated['recipient_address'],
                    'city' => $validated['recipient_city'],
                    'state' => $validated['recipient_state'],
                    'pincode' => $validated['recipient_pincode'],
                    'country' => 'India',
                    'type' => 'home',
                    'is_default' => auth()->user()->addresses()->count() === 0,
                ]);
            }

            CartItem::where('user_id', auth()->id())->delete();
            session()->forget('coupon_code');

            DB::commit();

            return redirect()
                ->route('order.confirmation', $order)
                ->with('success', 'Order placed successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while placing your order. Please try again.');
        }
    }

    private function claimSessionCartForUser(): void
    {
        $sessionId = session()->getId();

        CartItem::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->update([
                'user_id' => auth()->id(),
                'session_id' => null,
            ]);
    }

    private function validatedCartItems(bool $lockStock = false)
    {
        $cartController = new CartController();
        $cartItems = $cartController->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if ($lockStock) {
            $products = \App\Models\Product::whereIn('id', $cartItems->pluck('product_id')->unique())
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $variants = \App\Models\ProductVariant::whereIn(
                'id',
                $cartItems->pluck('variant_id')->filter()->unique()
            )
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cartItems as $item) {
                $item->setRelation('product', $products->get($item->product_id));
                $item->setRelation('variant', $variants->get($item->variant_id));
            }
        }

        foreach ($cartItems as $item) {
            $product = $item->product;

            if (!$product || !$product->is_active) {
                return redirect()->route('cart.index')->with(
                    'error',
                    'One or more products in your cart are no longer available.'
                );
            }

            if ($item->variant_id !== null && (
                !$item->variant || (int) $item->variant->product_id !== (int) $product->id
            )) {
                return redirect()->route('cart.index')->with(
                    'error',
                    'One or more variants in your cart are no longer available. Please update your cart.'
                );
            }

            if ($item->quantity < 1 || $item->quantity > 10) {
                return redirect()->route('cart.index')->with(
                    'error',
                    'Invalid quantity for "' . $product->name . '". Please update your cart.'
                );
            }

        }

        foreach ($cartItems->groupBy('product_id') as $productItems) {
            $product = $productItems->first()->product;
            $requestedQuantity = $productItems->sum('quantity');

            if ((int) $product->stock < $requestedQuantity) {
                return redirect()->route('cart.index')->with(
                    'error',
                    '"' . $product->name . '" only has ' . max(0, (int) $product->stock) . ' left in stock.'
                );
            }
        }

        foreach ($cartItems->filter(fn ($item) => $item->variant_id !== null)->groupBy('variant_id') as $variantItems) {
            $variant = $variantItems->first()->variant;
            $requestedQuantity = $variantItems->sum('quantity');

            if ((int) $variant->stock < $requestedQuantity) {
                return redirect()->route('cart.index')->with(
                    'error',
                    '"' . $variantItems->first()->product->name . '" only has ' . max(0, (int) $variant->stock) . ' left in stock for this variant.'
                );
            }
        }

        return $cartItems;
    }

    private function calculateTotals($cartItems, ?DeliverySlot $slot = null): array
    {
        $subtotal = (float) $cartItems->sum('total');
        $discount = 0.0;
        $coupon = null;

        if (session('coupon_code')) {
            $coupon = Coupon::where('code', session('coupon_code'))->first();
            if ($coupon && $coupon->isValid(auth()->user())) {
                $discount = (float) $coupon->calculateDiscount($subtotal);
            } else {
                $coupon = null;
            }
        }

        if ($slot) {
            $deliveryCharge = (float) $slot->extra_charge;
        } else {
            $deliveryCharge = self::STANDARD_DELIVERY_CHARGE;
        }

        if ($subtotal >= self::FREE_DELIVERY_MIN) {
            $deliveryCharge = 0.0;
        }

        $total = max(0, $subtotal - $discount + $deliveryCharge);

        return [$subtotal, $discount, $coupon, $deliveryCharge, $total];
    }
}
