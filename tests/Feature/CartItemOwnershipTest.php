<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartItemOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_update_another_users_cart_item(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = $this->createCartItem($this->createProduct(), ['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->patch(route('cart.update', $item), [
            'quantity' => 3,
        ]);

        $response->assertNotFound();
        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'user_id' => $owner->id,
            'quantity' => 1,
        ]);
    }

    public function test_user_cannot_remove_another_users_cart_item(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = $this->createCartItem($this->createProduct(), ['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->delete(route('cart.remove', $item));

        $response->assertNotFound();
        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_owner_can_update_and_remove_their_cart_item(): void
    {
        $owner = User::factory()->create();
        $item = $this->createCartItem($this->createProduct(), ['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->patch(route('cart.update', $item), ['quantity' => 3])
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 3,
        ]);

        $this->delete(route('cart.remove', $item))
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_guest_can_update_and_remove_items_in_their_session_but_not_another_session(): void
    {
        $product = $this->createProduct();
        $currentSessionId = Str::random(40);
        $this->withCookie(config('session.cookie'), $currentSessionId);
        $ownedItem = $this->createCartItem($product, [
            'session_id' => $currentSessionId,
        ]);
        $otherSessionItem = $this->createCartItem($product, [
            'session_id' => Str::random(40),
        ]);

        $this->patch(route('cart.update', $otherSessionItem), ['quantity' => 2])
            ->assertNotFound();
        $this->delete(route('cart.remove', $otherSessionItem))
            ->assertNotFound();
        $this->assertDatabaseHas('cart_items', [
            'id' => $otherSessionItem->id,
            'quantity' => 1,
        ]);

        $this->patch(route('cart.update', $ownedItem), ['quantity' => 2])
            ->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('cart_items', [
            'id' => $ownedItem->id,
            'quantity' => 2,
        ]);

        $this->delete(route('cart.remove', $ownedItem))
            ->assertRedirect(route('cart.index'));
        $this->assertDatabaseMissing('cart_items', ['id' => $ownedItem->id]);
    }

    private function createProduct(): Product
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
            'stock' => 10,
            'is_active' => true,
        ]);
    }

    private function createCartItem(Product $product, array $ownership): CartItem
    {
        return CartItem::create(array_merge([
            'product_id' => $product->id,
            'quantity' => 1,
        ], $ownership));
    }
}
