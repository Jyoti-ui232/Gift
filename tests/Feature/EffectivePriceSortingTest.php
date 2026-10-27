<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EffectivePriceSortingTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_page_sorts_by_effective_price_in_both_directions(): void
    {
        [$category, $products] = $this->createProductsForListings();

        $this->get(route('categories.show', ['category' => $category->slug, 'sort' => 'price_asc']))
            ->assertOk()
            ->assertSeeInOrder([
                $products['cheap-sale']->name,
                $products['valid-sale']->name,
                $products['invalid-sale']->name,
                $products['zero-sale']->name,
            ]);

        $this->get(route('categories.show', ['category' => $category->slug, 'sort' => 'price_desc']))
            ->assertOk()
            ->assertSeeInOrder([
                $products['zero-sale']->name,
                $products['invalid-sale']->name,
                $products['valid-sale']->name,
                $products['cheap-sale']->name,
            ]);
    }

    public function test_occasion_page_sorts_by_effective_price_in_both_directions(): void
    {
        [, $products, $occasion] = $this->createProductsForListings();

        $this->get(route('occasions.show', ['occasion' => $occasion->slug, 'sort' => 'price_asc']))
            ->assertOk()
            ->assertSeeInOrder([
                $products['cheap-sale']->name,
                $products['valid-sale']->name,
                $products['invalid-sale']->name,
                $products['zero-sale']->name,
            ]);

        $this->get(route('occasions.show', ['occasion' => $occasion->slug, 'sort' => 'price_desc']))
            ->assertOk()
            ->assertSeeInOrder([
                $products['zero-sale']->name,
                $products['invalid-sale']->name,
                $products['valid-sale']->name,
                $products['cheap-sale']->name,
            ]);
    }

    public function test_effective_price_accessor_and_query_expression_ignore_invalid_sale_prices(): void
    {
        [$category, $products] = $this->createProductsForListings();

        $this->assertSame(50.0, (float) $products['cheap-sale']->current_price);
        $this->assertSame(80.0, (float) $products['valid-sale']->current_price);
        $this->assertSame(120.0, (float) $products['invalid-sale']->current_price);
        $this->assertSame(130.0, (float) $products['zero-sale']->current_price);
        $this->assertSame(1, Product::active()->where('category_id', $category->id)
            ->whereRaw(Product::effectivePriceSql() . ' >= ?', [70])
            ->whereRaw(Product::effectivePriceSql() . ' <= ?', [85])
            ->count());
        $this->assertSame(1, Product::active()->where('category_id', $category->id)
            ->whereRaw(Product::effectivePriceSql() . ' >= ?', [125])
            ->whereRaw(Product::effectivePriceSql() . ' <= ?', [135])
            ->count());
    }

    public function test_zero_sale_price_uses_regular_price_in_product_listing_category_and_related_cards(): void
    {
        [$category, $products] = $this->createProductsForListings();

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee($products['zero-sale']->name)
            ->assertSee('₹130')
            ->assertDontSee('₹0');

        $this->get(route('categories.show', $category->slug))
            ->assertOk()
            ->assertSee($products['zero-sale']->name)
            ->assertSee('₹130')
            ->assertDontSee('₹0');

        $this->get(route('products.show', $products['valid-sale']))
            ->assertOk()
            ->assertSee($products['zero-sale']->name)
            ->assertSee('₹130')
            ->assertDontSee('₹0');

        $this->get(route('products.show', $products['zero-sale']))
            ->assertOk()
            ->assertSee('₹130')
            ->assertDontSee('₹0');
    }

    private function createProductsForListings(): array
    {
        $category = Category::create([
            'name' => 'Effective Price Category',
            'slug' => 'effective-price-category',
            'is_active' => true,
        ]);
        $occasion = Occasion::create([
            'name' => 'Effective Price Occasion',
            'slug' => 'effective-price-occasion',
            'is_active' => true,
        ]);

        $products = [
            'cheap-sale' => $this->createProduct($category, 'Cheap Sale Product', 'cheap-sale-product', 200, 50),
            'valid-sale' => $this->createProduct($category, 'Valid Sale Product', 'valid-sale-product', 100, 80),
            'invalid-sale' => $this->createProduct($category, 'Invalid Sale Product', 'invalid-sale-product', 120, 150),
            'zero-sale' => $this->createProduct($category, 'Zero Sale Product', 'zero-sale-product', 130, 0),
        ];

        foreach ($products as $product) {
            $product->occasions()->attach($occasion);
        }

        return [$category, $products, $occasion];
    }

    private function createProduct(
        Category $category,
        string $name,
        string $slug,
        float $price,
        float $salePrice
    ): Product {
        return Product::create([
            'name' => $name,
            'slug' => $slug,
            'category_id' => $category->id,
            'price' => $price,
            'sale_price' => $salePrice,
            'sku' => strtoupper($slug),
            'stock' => 5,
            'sort_order' => 0,
            'is_active' => true,
        ]);
    }
}
