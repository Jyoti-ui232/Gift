<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_product_with_variants_and_occasions(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory('Flowers', 'flowers');
        $occasion = $this->createOccasion('Birthday', 'birthday');

        $this->actingAs($admin)
            ->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('name="occasions[]"', false)
            ->assertSee('name="variants[0][name]"', false);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Birthday Roses',
                'category_id' => $category->id,
                'price' => 500,
                'sku' => 'BIRTHDAY-ROSES',
                'stock' => 10,
                'is_active' => 1,
                'occasions_present' => 1,
                'occasions' => [$occasion->id],
                'variants' => [
                    [
                        'name' => 'Small',
                        'type' => 'size',
                        'price' => 450,
                        'sale_price' => '',
                        'stock' => 4,
                        'sku' => 'BIRTHDAY-ROSES-S',
                        'is_default' => 1,
                    ],
                ],
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'BIRTHDAY-ROSES')->firstOrFail();
        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'name' => 'Small',
            'type' => 'size',
            'price' => 450,
            'stock' => 4,
            'is_default' => true,
        ]);
        $this->assertSame([$occasion->id], $product->occasions()->pluck('occasions.id')->all());
    }

    public function test_admin_can_update_variants_and_assign_or_remove_occasions(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory('Cakes', 'cakes');
        $oldOccasion = $this->createOccasion('Birthday', 'birthday');
        $newOccasion = $this->createOccasion('Anniversary', 'anniversary');
        $product = $this->createProduct($category);
        $product->update(['description' => 'Keep this product description.']);
        $product->occasions()->attach($oldOccasion);
        $existingVariant = $this->createVariant($product, 'Small', 'CAKE-S');

        $this->actingAs($admin)
            ->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee('name="variants[0][id]"', false)
            ->assertSee('value="' . $oldOccasion->id . '"', false);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'name' => $product->name,
                'category_id' => $category->id,
                'price' => 700,
                'sku' => $product->sku,
                'stock' => 8,
                'is_active' => 1,
                'occasions_present' => 1,
                'occasions' => [$newOccasion->id],
                'variants' => [
                    [
                        'id' => $existingVariant->id,
                        'name' => 'Medium',
                        'type' => 'size',
                        'price' => 650,
                        'sale_price' => '',
                        'stock' => 6,
                        'sku' => 'CAKE-M',
                        'is_default' => 1,
                    ],
                    [
                        'name' => 'Large',
                        'type' => 'size',
                        'price' => 900,
                        'sale_price' => '',
                        'stock' => 3,
                        'sku' => 'CAKE-L',
                    ],
                ],
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertSame('Keep this product description.', $product->fresh()->description);
        $this->assertSame([$newOccasion->id], $product->fresh()->occasions()->pluck('occasions.id')->all());
        $this->assertDatabaseHas('product_variants', [
            'id' => $existingVariant->id,
            'product_id' => $product->id,
            'name' => 'Medium',
            'price' => 650,
            'stock' => 6,
        ]);
        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'name' => 'Large',
            'price' => 900,
            'stock' => 3,
        ]);

        $this->put(route('admin.products.update', $product), [
            'name' => $product->name,
            'category_id' => $category->id,
            'price' => 700,
            'sku' => $product->sku,
            'occasions_present' => 1,
        ])->assertRedirect(route('admin.products.index'));

        $this->assertSame([], $product->fresh()->occasions()->pluck('occasions.id')->all());
        $this->assertSame(2, $product->variants()->count());
    }

    public function test_admin_cannot_update_a_variant_owned_by_another_product(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory('Gifts', 'gifts');
        $product = $this->createProduct($category);
        $otherProduct = $this->createProduct($category, 'Other Product', 'OTHER-PRODUCT');
        $otherVariant = $this->createVariant($otherProduct, 'Other Size', 'OTHER-S');

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'name' => $product->name,
                'category_id' => $category->id,
                'price' => 500,
                'sku' => $product->sku,
                'variants' => [
                    [
                        'id' => $otherVariant->id,
                        'name' => 'Tampered',
                        'type' => 'size',
                        'price' => 1,
                        'stock' => 0,
                    ],
                ],
            ])
            ->assertSessionHasErrors('variants.0.id');

        $this->assertSame('Other Size', $otherVariant->fresh()->name);
        $this->assertDatabaseMissing('product_variants', [
            'product_id' => $product->id,
            'name' => 'Tampered',
        ]);
    }

    public function test_admin_can_assign_replace_and_clear_additional_categories(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $primaryCategory = $this->createCategory('Flowers', 'flowers');
        $firstAdditionalCategory = $this->createCategory('Roses', 'roses');
        $secondAdditionalCategory = $this->createCategory('Bouquets', 'bouquets');
        $product = $this->createProduct($primaryCategory, 'Red Rose Bouquet', 'RED-ROSE-BOUQUET');

        $this->actingAs($admin)
            ->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee('name="additional_categories[]"', false);

        $this->from(route('admin.products.create'))
            ->post(route('admin.products.store'), [
                'name' => 'Invalid Category Selection',
                'category_id' => $primaryCategory->id,
                'additional_categories_present' => 1,
                'additional_categories' => [$primaryCategory->id],
                'price' => 500,
                'sku' => 'INVALID-CATEGORY-SELECTION',
                'stock' => 5,
            ])
            ->assertSessionHasErrors('additional_categories.0');

        $this->post(route('admin.products.store'), [
            'name' => 'Pink Rose Bouquet',
            'category_id' => $primaryCategory->id,
            'additional_categories_present' => 1,
            'additional_categories' => [$firstAdditionalCategory->id, $secondAdditionalCategory->id],
            'price' => 500,
            'sku' => 'PINK-ROSE-BOUQUET',
            'stock' => 5,
        ])->assertRedirect(route('admin.products.index'));

        $createdProduct = Product::where('sku', 'PINK-ROSE-BOUQUET')->firstOrFail();
        $this->assertSame($primaryCategory->id, $createdProduct->category_id);
        $this->assertEqualsCanonicalizing(
            [$firstAdditionalCategory->id, $secondAdditionalCategory->id],
            $createdProduct->additionalCategories()->pluck('categories.id')->all()
        );
        $this->get(route('admin.products.index', ['category_id' => $firstAdditionalCategory->id]))
            ->assertOk()
            ->assertSee($createdProduct->name);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'name' => $product->name,
                'category_id' => $primaryCategory->id,
                'additional_categories_present' => 1,
                'additional_categories' => [$secondAdditionalCategory->id],
                'price' => 500,
                'sku' => $product->sku,
                'stock' => 5,
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertSame($primaryCategory->id, $product->fresh()->category_id);
        $this->assertSame(
            [$secondAdditionalCategory->id],
            $product->fresh()->additionalCategories()->pluck('categories.id')->all()
        );

        $this->put(route('admin.products.update', $product), [
            'name' => $product->name,
            'category_id' => $primaryCategory->id,
            'additional_categories_present' => 1,
            'price' => 500,
            'sku' => $product->sku,
            'stock' => 5,
        ])->assertRedirect(route('admin.products.index'));

        $this->assertSame([], $product->fresh()->additionalCategories()->pluck('categories.id')->all());
    }

    private function createCategory(string $name, string $slug): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'is_active' => true,
        ]);
    }

    private function createOccasion(string $name, string $slug): Occasion
    {
        return Occasion::create([
            'name' => $name,
            'slug' => $slug,
            'is_active' => true,
        ]);
    }

    private function createProduct(
        Category $category,
        string $name = 'Test Product',
        string $sku = 'TEST-PRODUCT'
    ): Product {
        static $productNumber = 0;

        return Product::create([
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)) . '-' . ++$productNumber,
            'category_id' => $category->id,
            'price' => 500,
            'sku' => $sku,
            'stock' => 5,
            'is_active' => true,
        ]);
    }

    private function createVariant(Product $product, string $name, string $sku): ProductVariant
    {
        static $variantNumber = 0;

        return ProductVariant::create([
            'product_id' => $product->id,
            'name' => $name,
            'type' => 'size',
            'price' => 500,
            'stock' => 5,
            'sku' => $sku . '-' . ++$variantNumber,
        ]);
    }
}
