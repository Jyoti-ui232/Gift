<?php

namespace Tests\Feature;

use App\Models\AddOn;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartAddMergeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCookie(config('session.cookie'), Str::random(40));
    }

    public function test_adding_same_product_with_same_options_merges_quantity(): void
    {
        $product = $this->createProduct();
        $addOn = $this->createAddOn();
        $this->addToCart($product, ['add_ons' => [$addOn->id], 'gift_message' => 'Happy birthday']);

        $this->addToCart($product, [
            'add_ons' => [$addOn->id],
            'gift_message' => 'Happy birthday',
            'quantity' => 2,
        ])->assertRedirect(route('cart.index'));

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 3,
            'gift_message' => 'Happy birthday',
        ]);
    }

    public function test_different_add_ons_create_a_separate_cart_line(): void
    {
        $product = $this->createProduct();
        $firstAddOn = $this->createAddOn('Gift Wrap');
        $secondAddOn = $this->createAddOn('Greeting Card');
        $this->addToCart($product, ['add_ons' => [$firstAddOn->id]]);

        $this->addToCart($product, ['add_ons' => [$secondAddOn->id]])
            ->assertRedirect(route('cart.index'));

        $items = CartItem::where('product_id', $product->id)->orderBy('id')->get();
        $this->assertCount(2, $items);
        $this->assertSame([$firstAddOn->id], collect($items[0]->add_ons)->pluck('id')->all());
        $this->assertSame([$secondAddOn->id], collect($items[1]->add_ons)->pluck('id')->all());
        $this->assertSame(1, $items[0]->quantity);
        $this->assertSame(1, $items[1]->quantity);
    }

    public function test_different_gift_messages_create_a_separate_cart_line(): void
    {
        $product = $this->createProduct();
        $this->addToCart($product, ['gift_message' => 'First message']);

        $this->addToCart($product, ['gift_message' => 'Second message'])
            ->assertRedirect(route('cart.index'));

        $items = CartItem::where('product_id', $product->id)->orderBy('id')->get();
        $this->assertCount(2, $items);
        $this->assertSame(['First message', 'Second message'], $items->pluck('gift_message')->all());
    }

    public function test_matching_line_can_be_added_up_to_ten_items(): void
    {
        $product = $this->createProduct();
        $this->addToCart($product, ['quantity' => 8]);

        $this->addToCart($product, ['quantity' => 2])
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);
    }

    public function test_addition_that_exceeds_ten_items_is_rejected_without_changing_the_line(): void
    {
        $product = $this->createProduct();
        $this->addToCart($product, ['quantity' => 9]);

        $response = $this->addToCart($product, ['quantity' => 2]);

        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 9,
        ]);
    }

    public function test_different_options_can_start_a_separate_line_within_the_per_line_limit(): void
    {
        $product = $this->createProduct();
        $addOn = $this->createAddOn();
        $this->addToCart($product, ['quantity' => 10]);

        $this->addToCart($product, [
            'quantity' => 10,
            'add_ons' => [$addOn->id],
        ])->assertRedirect(route('cart.index'));

        $this->assertDatabaseCount('cart_items', 2);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 10,
            'add_ons' => json_encode([[
                'id' => $addOn->id,
                'name' => $addOn->name,
                'price' => (float) $addOn->price,
            ]]),
        ]);
    }

    private function addToCart(Product $product, array $options = [])
    {
        return $this->post(route('cart.add'), array_merge([
            'product_id' => $product->id,
            'quantity' => 1,
        ], $options));
    }

    private function createProduct(): Product
    {
        $slug = 'test-product-' . Str::lower(Str::random(8));
        $category = Category::create([
            'name' => 'Test Category ' . $slug,
            'slug' => $slug . '-category',
        ]);

        return Product::create([
            'name' => 'Test Product ' . $slug,
            'slug' => $slug,
            'category_id' => $category->id,
            'price' => 500,
            'sku' => strtoupper($slug),
            'stock' => 100,
            'is_active' => true,
        ]);
    }

    private function createAddOn(string $name = 'Gift Add-on'): AddOn
    {
        return AddOn::create([
            'name' => $name . ' ' . Str::lower(Str::random(6)),
            'price' => 50,
            'type' => 'card',
            'is_active' => true,
        ]);
    }
}
