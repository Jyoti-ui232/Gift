<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartVariantValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_adding_a_product_with_another_products_variant_is_rejected(): void
    {
        $product = $this->createProduct('Product One', 'product-one');
        $otherProduct = $this->createProduct('Product Two', 'product-two');
        $variant = $this->createVariant($otherProduct);

        $response = $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
        ]);

        $response->assertSessionHasErrors('variant_id');
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_adding_a_product_without_variants_is_accepted(): void
    {
        $product = $this->createProduct('Product Without Variants', 'product-without-variants');

        $response = $this->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'variant_id' => null,
            'quantity' => 1,
        ]);
    }

    public function test_checkout_rejects_a_cart_variant_from_another_product(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct('Product One', 'product-one');
        $otherProduct = $this->createProduct('Product Two', 'product-two');
        $variant = $this->createVariant($otherProduct);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'), $this->checkoutDetails());

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas(
            'error',
            'One or more variants in your cart are no longer available. Please update your cart.'
        );
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_accepts_a_cart_item_without_a_variant(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct('Product Without Variants', 'product-without-variants');

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'variant_id' => null,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'), $this->checkoutDetails());

        $order = Order::firstOrFail();
        $response->assertRedirect(route('order.confirmation', $order));
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_name' => null,
            'quantity' => 1,
        ]);
    }

    private function createProduct(string $name, string $slug): Product
    {
        $category = Category::create([
            'name' => $name . ' Category',
            'slug' => $slug . '-category',
        ]);

        return Product::create([
            'name' => $name,
            'slug' => $slug,
            'category_id' => $category->id,
            'price' => 500,
            'sku' => strtoupper($slug),
            'stock' => 10,
            'is_active' => true,
        ]);
    }

    private function createVariant(Product $product): ProductVariant
    {
        return ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Small',
            'type' => 'size',
            'price' => 450,
            'stock' => 10,
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
