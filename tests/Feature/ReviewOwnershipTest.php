<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReviewOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_review_a_product_from_their_own_order(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createOrder($user, [$product]);

        $this->actingAs($user)
            ->post(route('reviews.store'), $this->reviewData($product, $order))
            ->assertRedirect()
            ->assertSessionHas('success', 'Review submitted! It will appear after approval.');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
        ]);
    }

    public function test_customer_cannot_review_using_another_users_order(): void
    {
        $user = User::factory()->create();
        $orderOwner = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createOrder($orderOwner, [$product]);

        $this->actingAs($user)
            ->post(route('reviews.store'), $this->reviewData($product, $order))
            ->assertForbidden();

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_customer_cannot_review_a_product_missing_from_their_order(): void
    {
        $user = User::factory()->create();
        $orderedProduct = $this->createProduct();
        $requestedProduct = $this->createProduct();
        $order = $this->createOrder($user, [$orderedProduct]);

        $this->actingAs($user)
            ->post(route('reviews.store'), $this->reviewData($requestedProduct, $order))
            ->assertForbidden();

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_tampered_user_and_invalid_order_or_product_ids_are_rejected_or_ignored(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createOrder($user, [$product]);

        $this->actingAs($user)
            ->post(route('reviews.store'), $this->reviewData($product, $order, [
                'user_id' => $otherUser->id,
            ]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
        ]);
        $this->assertDatabaseMissing('reviews', ['user_id' => $otherUser->id]);

        $this->post(route('reviews.store'), $this->reviewData($product, $order, [
            'order_id' => 999999,
            'product_id' => 999999,
        ]))->assertSessionHasErrors(['order_id', 'product_id']);
    }

    public function test_customer_cannot_submit_a_second_review_for_the_same_product(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $order = $this->createOrder($user, [$product]);
        $data = $this->reviewData($product, $order);

        $this->actingAs($user)
            ->post(route('reviews.store'), $data)
            ->assertSessionHas('success');

        $this->post(route('reviews.store'), $data)
            ->assertSessionHas('error', 'You have already reviewed this product.');

        $this->assertDatabaseCount('reviews', 1);
    }

    public function test_product_review_form_lists_only_the_customers_matching_orders(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = $this->createProduct();
        $orderedProduct = $this->createProduct();
        $matchingOrder = $this->createOrder($user, [$product]);
        $nonMatchingOrder = $this->createOrder($user, [$orderedProduct]);
        $otherUsersOrder = $this->createOrder($otherUser, [$product]);

        $this->actingAs($user)
            ->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('name="order_id"', false)
            ->assertSee('name="rating"', false)
            ->assertSee('type="radio"', false)
            ->assertSee($matchingOrder->order_number)
            ->assertDontSee($nonMatchingOrder->order_number)
            ->assertDontSee($otherUsersOrder->order_number);
    }

    private function reviewData(Product $product, Order $order, array $overrides = []): array
    {
        return array_replace([
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
            'title' => 'Excellent',
            'comment' => 'A lovely product.',
        ], $overrides);
    }

    private function createProduct(): Product
    {
        static $counter = 0;
        $counter++;

        $category = Category::create([
            'name' => 'Review Category ' . $counter,
            'slug' => 'review-category-' . Str::uuid(),
        ]);

        return Product::create([
            'name' => 'Review Product ' . $counter,
            'slug' => 'review-product-' . Str::uuid(),
            'category_id' => $category->id,
            'price' => 25,
            'sku' => 'REVIEW-' . Str::uuid(),
            'stock' => 10,
            'is_active' => true,
        ]);
    }

    private function createOrder(User $user, array $products): Order
    {
        static $counter = 0;
        $counter++;

        $order = Order::create([
            'order_number' => 'REVIEW-' . $counter . '-' . Str::upper(Str::random(8)),
            'user_id' => $user->id,
            'subtotal' => 25,
            'total' => 25,
            'recipient_name' => 'Review Recipient',
            'recipient_phone' => '9876543210',
            'recipient_address' => '123 Review Street',
            'recipient_city' => 'Delhi',
            'recipient_state' => 'Delhi',
            'recipient_pincode' => '110001',
        ]);

        foreach ($products as $product) {
            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => 25,
                'quantity' => 1,
                'total' => 25,
            ]);
        }

        return $order;
    }
}
