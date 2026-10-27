<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_creation_rejects_invalid_stock_and_price_values(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory();

        foreach ($this->invalidPriceAndStockInputs() as $index => [$field, $value, $price]) {
            $response = $this->actingAs($admin)
                ->from(route('admin.products.create'))
                ->post(route('admin.products.store'), $this->productData($category, [
                    'sku' => 'CREATE-INVALID-' . $index,
                    'price' => $price,
                    $field => $value,
                ]));

            $response->assertSessionHasErrors($field);
        }

        $this->assertDatabaseCount('products', 0);
    }

    public function test_product_update_rejects_invalid_stock_and_price_values(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory();
        $product = $this->createProduct($category);

        foreach ($this->invalidPriceAndStockInputs() as [$field, $value, $price]) {
            $response = $this->actingAs($admin)
                ->from(route('admin.products.edit', $product))
                ->put(route('admin.products.update', $product), $this->productData($category, [
                    'name' => 'Changed Name',
                    'price' => $price,
                    $field => $value,
                ]));

            $response->assertSessionHasErrors($field);
        }

        $this->assertSame('Original Product', $product->fresh()->name);
        $this->assertSame(100.0, (float) $product->fresh()->price);
        $this->assertSame(10, (int) $product->fresh()->stock);
    }

    public function test_unapproved_product_fields_are_not_persisted_on_create_or_update(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory();
        $unapprovedFields = [
            'delivery_type' => 'midnight',
            'rating' => 5,
            'reviews_count' => 900,
            'sort_order' => 999,
            'meta_title' => 'Unapproved title',
            'slug' => 'attacker-controlled-slug',
        ];

        $this->actingAs($admin)
            ->post(route('admin.products.store'), $this->productData($category, $unapprovedFields))
            ->assertRedirect(route('admin.products.index'));

        $createdProduct = Product::where('sku', 'VALID-PRODUCT')->firstOrFail();
        $this->assertNotSame('attacker-controlled-slug', $createdProduct->slug);
        $this->assertSame('standard', $createdProduct->delivery_type);
        $this->assertSame(0.0, (float) $createdProduct->rating);
        $this->assertSame(0, $createdProduct->reviews_count);
        $this->assertSame(0, $createdProduct->sort_order);
        $this->assertNull($createdProduct->meta_title);

        $this->put(route('admin.products.update', $createdProduct), $this->productData($category, [
            'name' => $createdProduct->name,
            'sku' => $createdProduct->sku,
            ...$unapprovedFields,
        ]))->assertRedirect(route('admin.products.index'));

        $createdProduct->refresh();
        $this->assertNotSame('attacker-controlled-slug', $createdProduct->slug);
        $this->assertSame('standard', $createdProduct->delivery_type);
        $this->assertSame(0.0, (float) $createdProduct->rating);
        $this->assertSame(0, $createdProduct->reviews_count);
        $this->assertSame(0, $createdProduct->sort_order);
        $this->assertNull($createdProduct->meta_title);
    }

    public function test_two_decimal_prices_at_the_database_limit_are_accepted(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), $this->productData($category, [
                'price' => '99999999.99',
                'sale_price' => '99999999.99',
                'sku' => 'MAX-PRICE-PRODUCT',
                'stock' => 0,
            ]))
            ->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'MAX-PRICE-PRODUCT')->firstOrFail();
        $this->assertSame(99999999.99, (float) $product->price);
        $this->assertSame(99999999.99, (float) $product->sale_price);

        $this->put(route('admin.products.update', $product), $this->productData($category, [
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => '100.25',
            'sale_price' => '',
            'stock' => 1,
        ]))->assertRedirect(route('admin.products.index'));

        $this->assertSame(100.25, (float) $product->fresh()->price);
        $this->assertNull($product->fresh()->sale_price);
    }

    public function test_variant_prices_and_stock_respect_decimal_limits_and_sale_price_relationship(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory();

        foreach ([
            ['price' => 100, 'sale_price' => 0, 'stock' => 1, 'expected' => 'variants.0.sale_price'],
            ['price' => 100, 'sale_price' => 101, 'stock' => 1, 'expected' => 'variants.0.sale_price'],
            ['price' => 1.234, 'sale_price' => null, 'stock' => 1, 'expected' => 'variants.0.price'],
            ['price' => 100, 'sale_price' => null, 'stock' => 1.5, 'expected' => 'variants.0.stock'],
        ] as $index => $variant) {
            $response = $this->actingAs($admin)
                ->from(route('admin.products.create'))
                ->post(route('admin.products.store'), $this->productData($category, [
                    'sku' => 'INVALID-VARIANT-' . $index,
                    'variants' => [[
                        'name' => 'Small',
                        'type' => 'size',
                        'price' => $variant['price'],
                        'sale_price' => $variant['sale_price'],
                        'stock' => $variant['stock'],
                    ]],
                ]));

            $response->assertSessionHasErrors($variant['expected']);
        }

        $this->assertDatabaseCount('products', 0);
    }

    private function invalidPriceAndStockInputs(): array
    {
        return [
            ['stock', -1, 100],
            ['stock', 1.5, 100],
            ['price', -1, -1],
            ['price', 100000000, 100000000],
            ['price', 1.234, 1.234],
            ['sale_price', -1, 100],
            ['sale_price', 0, 100],
            ['sale_price', 101, 100],
            ['sale_price', 100000000, 100],
            ['sale_price', 1.234, 100],
        ];
    }

    private function productData(Category $category, array $overrides = []): array
    {
        return array_replace([
            'name' => 'Valid Product',
            'category_id' => $category->id,
            'price' => 100,
            'sale_price' => '',
            'stock' => 5,
            'sku' => 'VALID-PRODUCT',
            'is_active' => 1,
        ], $overrides);
    }

    private function createCategory(): Category
    {
        return Category::create([
            'name' => 'Validation Category',
            'slug' => 'validation-category',
            'is_active' => true,
        ]);
    }

    private function createProduct(Category $category): Product
    {
        return Product::create([
            'name' => 'Original Product',
            'slug' => 'original-product',
            'category_id' => $category->id,
            'price' => 100,
            'sku' => 'ORIGINAL-PRODUCT',
            'stock' => 10,
            'is_active' => true,
        ]);
    }
}
