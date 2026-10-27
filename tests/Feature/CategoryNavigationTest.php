<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_subcategory_link_uses_the_existing_category_route(): void
    {
        $parent = $this->createCategory('Flowers', 'flowers');
        $child = $this->createCategory('Roses', 'roses', $parent->id);

        $response = $this->get(route('categories.show', $parent->slug));

        $response->assertOk()
            ->assertSee('href="' . route('categories.show', $child->slug) . '"', false);

        $this->get(route('categories.show', $child->slug))
            ->assertOk()
            ->assertSee($child->name);
    }

    public function test_product_occasion_and_information_routes_still_resolve(): void
    {
        $category = $this->createCategory('Gifts', 'gifts');
        $product = Product::create([
            'name' => 'Birthday Gift',
            'slug' => 'birthday-gift',
            'category_id' => $category->id,
            'price' => 500,
            'sku' => 'BIRTHDAY-GIFT',
            'stock' => 5,
            'is_active' => true,
        ]);
        $occasion = Occasion::create([
            'name' => 'Birthday',
            'slug' => 'birthday',
            'is_active' => true,
        ]);

        $this->get(route('products.index'))->assertOk();
        $this->get(route('products.show', $product->slug))->assertOk();
        $this->get(route('occasions.show', $occasion->slug))->assertOk();
        $this->get(route('pages.about'))->assertOk();
    }

    public function test_category_pages_include_primary_and_additional_products_once(): void
    {
        $flowers = $this->createCategory('Flowers', 'flowers');
        $roses = $this->createCategory('Roses', 'roses', $flowers->id);
        $activeProduct = Product::create([
            'name' => 'Red Rose Bouquet',
            'slug' => 'red-rose-bouquet',
            'category_id' => $flowers->id,
            'price' => 500,
            'sku' => 'RED-ROSE-BOUQUET',
            'stock' => 5,
            'is_active' => true,
        ]);
        $inactiveProduct = Product::create([
            'name' => 'Inactive Rose Bouquet',
            'slug' => 'inactive-rose-bouquet',
            'category_id' => $flowers->id,
            'price' => 500,
            'sku' => 'INACTIVE-ROSE-BOUQUET',
            'stock' => 5,
            'is_active' => false,
        ]);

        $activeProduct->additionalCategories()->attach([$flowers->id, $roses->id]);
        $inactiveProduct->additionalCategories()->attach($roses);

        foreach ([$flowers, $roses] as $category) {
            $response = $this->get(route('categories.show', $category->slug));

            $response->assertOk()->assertSee($activeProduct->name)->assertDontSee($inactiveProduct->name);
            $productCard = 'data-product-url="' . route('products.show', $activeProduct) . '"';
            $this->assertSame(1, substr_count($response->getContent(), $productCard));
        }

        $this->get(route('products.index', ['category' => $roses->slug]))
            ->assertOk()
            ->assertSee($activeProduct->name);
    }

    private function createCategory(string $name, string $slug, ?int $parentId = null): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $parentId,
            'is_active' => true,
        ]);
    }
}
