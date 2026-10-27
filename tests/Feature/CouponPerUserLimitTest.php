<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponPerUserLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_at_coupon_limit_cannot_apply_coupon(): void
    {
        $user = User::factory()->create();
        $coupon = $this->createCoupon();
        $this->createOrderUsingCoupon($user, $coupon);

        $this->actingAs($user)
            ->post(route('cart.coupon'), ['code' => $coupon->code])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error', 'Invalid or expired coupon code.')
            ->assertSessionMissing('coupon_code');
    }

    public function test_user_below_coupon_limit_can_apply_coupon(): void
    {
        $user = User::factory()->create();
        $coupon = $this->createCoupon();
        $product = $this->createProduct();
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->post(route('cart.coupon'), ['code' => $coupon->code])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('success', 'Coupon applied successfully!')
            ->assertSessionHas('coupon_code', $coupon->code);

        $this->post(route('checkout.store'), $this->checkoutDetails())
            ->assertRedirect(route('order.confirmation', Order::firstOrFail()));

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'coupon_code' => $coupon->code,
            'discount' => 100,
        ]);
        $this->assertSame(1, (int) $coupon->fresh()->used_count);
    }

    public function test_guest_can_apply_coupon_but_authenticated_checkout_rechecks_user_limit(): void
    {
        $coupon = $this->createCoupon();

        $this->post(route('cart.coupon'), ['code' => $coupon->code])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('coupon_code', $coupon->code);

        $user = User::factory()->create();
        $this->createOrderUsingCoupon($user, $coupon);
        $product = $this->createProduct();
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->withSession(['coupon_code' => $coupon->code])
            ->post(route('checkout.store'), $this->checkoutDetails())
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error', 'You have reached the usage limit for this coupon.')
            ->assertSessionMissing('coupon_code');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 0);

        auth()->logout();
        $this->post(route('checkout.store'), $this->checkoutDetails())
            ->assertRedirect(route('login'));
    }

    private function createCoupon(): Coupon
    {
        return Coupon::create([
            'code' => 'SAVE10',
            'type' => 'fixed',
            'value' => 100,
            'min_order_amount' => 0,
            'used_count' => 0,
            'per_user_limit' => 1,
            'is_active' => true,
        ]);
    }

    private function createOrderUsingCoupon(User $user, Coupon $coupon): Order
    {
        static $orderNumber = 0;

        return Order::create([
            'order_number' => 'TEST-COUPON-' . ++$orderNumber,
            'user_id' => $user->id,
            'status' => 'pending',
            'subtotal' => 500,
            'discount' => 100,
            'delivery_charge' => 49,
            'total' => 449,
            'coupon_code' => $coupon->code,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '9876543210',
            'recipient_address' => '123 Test Street',
            'recipient_city' => 'Delhi',
            'recipient_state' => 'Delhi',
            'recipient_pincode' => '110001',
            'delivery_type' => 'standard',
        ]);
    }

    private function createProduct(): Product
    {
        $category = Category::create([
            'name' => 'Coupon Category',
            'slug' => 'coupon-category',
        ]);

        return Product::create([
            'name' => 'Coupon Test Product',
            'slug' => 'coupon-test-product',
            'category_id' => $category->id,
            'price' => 500,
            'sku' => 'COUPON-TEST-PRODUCT',
            'stock' => 5,
            'is_active' => true,
        ]);
    }

    private function checkoutDetails(): array
    {
        return [
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '9876543210',
            'recipient_address' => '123 Test Street',
            'recipient_city' => 'Delhi',
            'recipient_state' => 'Delhi',
            'recipient_pincode' => '110001',
            'delivery_date' => now()->addDay()->toDateString(),
            'payment_method' => 'cod',
        ];
    }
}
