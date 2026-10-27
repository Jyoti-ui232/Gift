<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\DeliverySlot;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_decrements_product_stock_once_for_a_product_without_variants(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 8);
        $this->createCartItem($user, $product, quantity: 3);

        $this->checkoutAs($user)->assertRedirect(route('order.confirmation', Order::firstOrFail()));

        $this->assertSame(5, (int) $product->fresh()->stock);
        $this->assertDatabaseCount('order_items', 1);
    }

    public function test_checkout_decrements_product_and_variant_stock_once_for_variant_items(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 10);
        $variant = $this->createVariant($product, stock: 6);
        $this->createCartItem($user, $product, $variant, quantity: 2);
        $this->createCartItem($user, $product, $variant, quantity: 1);

        $this->checkoutAs($user)->assertRedirect(route('order.confirmation', Order::firstOrFail()));

        $this->assertSame(7, (int) $product->fresh()->stock);
        $this->assertSame(3, (int) $variant->fresh()->stock);
        $this->assertDatabaseCount('order_items', 2);
        $this->assertDatabaseHas('order_items', [
            'product_variant_id' => $variant->id,
            'variant_name' => $variant->name,
        ]);
    }

    public function test_zero_product_sale_price_uses_regular_price_in_cart_checkout_and_order_item(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 8);
        $product->update(['sale_price' => 0]);
        $this->createCartItem($user, $product);

        $this->actingAs($user)
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('₹500 each')
            ->assertDontSee('₹0 each');

        $this->actingAs($user)
            ->get(route('checkout.index'))
            ->assertOk()
            ->assertSee('Qty: 1 × ₹500')
            ->assertDontSee('Qty: 1 × ₹0');

        $this->checkoutAs($user)
            ->assertRedirect(route('order.confirmation', Order::firstOrFail()));

        $order = Order::firstOrFail();
        $this->assertSame(500.0, (float) $order->subtotal);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => 500,
            'total' => 500,
        ]);
    }

    public function test_zero_variant_sale_price_uses_regular_price_in_cart_checkout_and_order_item(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 8);
        $product->update(['sale_price' => 0]);
        $variant = $this->createVariant($product, stock: 5);
        $variant->update(['sale_price' => 0]);
        $this->createCartItem($user, $product, $variant);

        $this->actingAs($user)
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('₹450 each')
            ->assertDontSee('₹0 each');

        $this->actingAs($user)
            ->get(route('checkout.index'))
            ->assertOk()
            ->assertSee('Qty: 1 × ₹450')
            ->assertDontSee('Qty: 1 × ₹0');

        $this->checkoutAs($user)
            ->assertRedirect(route('order.confirmation', Order::firstOrFail()));

        $order = Order::firstOrFail();
        $this->assertSame(450.0, (float) $order->subtotal);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'price' => 450,
            'total' => 450,
        ]);
    }

    public function test_checkout_rejects_inactive_delivery_slot_without_creating_an_order(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 8);
        $this->createCartItem($user, $product);
        $inactiveSlot = $this->createDeliverySlot(isActive: false);

        $response = $this->checkoutAs($user, $inactiveSlot->id);

        $response->assertSessionHasErrors('delivery_slot_id');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertSame(8, (int) $product->fresh()->stock);
    }

    public function test_checkout_accepts_active_delivery_slot_and_saves_its_details(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 8);
        $this->createCartItem($user, $product);
        $activeSlot = $this->createDeliverySlot(
            isActive: true,
            type: 'express',
            timeRange: '10:00 AM - 12:00 PM',
            extraCharge: 75
        );

        $this->checkoutAs($user, $activeSlot->id)
            ->assertRedirect(route('order.confirmation', Order::firstOrFail()));

        $order = Order::firstOrFail();
        $this->assertSame('express', $order->delivery_type);
        $this->assertSame('10:00 AM - 12:00 PM', $order->delivery_slot);
        $this->assertSame(75.0, (float) $order->delivery_charge);
    }

    public function test_checkout_allows_omitting_delivery_slot_when_none_are_active(): void
    {
        $user = User::factory()->create();
        $this->createCartItem($user, $this->createProduct(stock: 8));
        $this->createDeliverySlot(isActive: false);

        $this->checkoutAs($user)
            ->assertRedirect(route('order.confirmation', Order::firstOrFail()));

        $order = Order::firstOrFail();
        $this->assertSame('standard', $order->delivery_type);
        $this->assertNull($order->delivery_slot);
    }

    public function test_checkout_rejects_when_aggregate_product_stock_is_insufficient(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 1);
        $firstVariant = $this->createVariant($product, stock: 5);
        $secondVariant = $this->createVariant($product, stock: 5);
        $this->createCartItem($user, $product, $firstVariant);
        $this->createCartItem($user, $product, $secondVariant);

        $this->assertCheckoutRejected($user);

        $this->assertSame(1, (int) $product->fresh()->stock);
        $this->assertSame(5, (int) $firstVariant->fresh()->stock);
        $this->assertSame(5, (int) $secondVariant->fresh()->stock);
    }

    public function test_checkout_rejects_when_combined_variant_quantity_exceeds_variant_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 10);
        $variant = $this->createVariant($product, stock: 1);
        $this->createCartItem($user, $product, $variant);
        $this->createCartItem($user, $product, $variant);

        $this->assertCheckoutRejected($user);

        $this->assertSame(10, (int) $product->fresh()->stock);
        $this->assertSame(1, (int) $variant->fresh()->stock);
    }

    public function test_checkout_rejects_combined_quantity_across_cart_rows_when_product_stock_is_insufficient(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(stock: 2);
        $variant = $this->createVariant($product, stock: 10);
        $this->createCartItem($user, $product, $variant, quantity: 1);
        $this->createCartItem($user, $product, $variant, quantity: 2);

        $this->assertCheckoutRejected($user);

        $this->assertSame(2, (int) $product->fresh()->stock);
        $this->assertSame(10, (int) $variant->fresh()->stock);
    }

    private function assertCheckoutRejected(User $user): void
    {
        $response = $this->checkoutAs($user);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
    }

    private function checkoutAs(User $user, ?int $deliverySlotId = null)
    {
        $details = [
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '9876543210',
            'recipient_address' => '123 Test Street',
            'recipient_city' => 'Delhi',
            'recipient_state' => 'Delhi',
            'recipient_pincode' => '110001',
            'delivery_date' => now()->addDay()->toDateString(),
            'payment_method' => 'cod',
        ];

        if ($deliverySlotId !== null) {
            $details['delivery_slot_id'] = $deliverySlotId;
        }

        return $this->actingAs($user)->post(route('checkout.store'), $details);
    }

    private function createProduct(int $stock): Product
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        return Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'category_id' => $category->id,
            'price' => 500,
            'sku' => 'TEST-PRODUCT',
            'stock' => $stock,
            'is_active' => true,
        ]);
    }

    private function createVariant(Product $product, int $stock): ProductVariant
    {
        static $variantNumber = 0;

        return ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Variant ' . ++$variantNumber,
            'type' => 'size',
            'price' => 450,
            'stock' => $stock,
        ]);
    }

    private function createDeliverySlot(
        bool $isActive,
        string $type = 'standard',
        string $timeRange = '9:00 AM - 12:00 PM',
        float $extraCharge = 0
    ): DeliverySlot {
        static $slotNumber = 0;

        return DeliverySlot::create([
            'name' => 'Slot ' . ++$slotNumber,
            'time_range' => $timeRange,
            'type' => $type,
            'extra_charge' => $extraCharge,
            'is_active' => $isActive,
        ]);
    }

    private function createCartItem(
        User $user,
        Product $product,
        ?ProductVariant $variant = null,
        int $quantity = 1
    ): CartItem {
        return CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'quantity' => $quantity,
        ]);
    }
}
