<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ProductFallbackImageUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        URL::forceRootUrl(null);

        parent::tearDown();
    }

    public function test_product_fallback_image_url_works_at_domain_root(): void
    {
        $product = $this->createProduct();
        URL::forceRootUrl('http://localhost');

        $this->assertSame(
            'http://localhost/assets/image/roses.jpg',
            $product->thumbnail_url
        );
    }

    public function test_product_detail_fallback_image_url_includes_application_subdirectory(): void
    {
        $product = $this->createProduct();
        URL::forceRootUrl('http://localhost/giftbloom/public');

        $this->get('http://localhost/products/' . $product->slug)
            ->assertOk()
            ->assertSee(
                'src="http://localhost/giftbloom/public/assets/image/roses.jpg"',
                false
            );
    }

    public function test_product_listing_fallback_image_url_includes_application_subdirectory(): void
    {
        $this->createProduct();
        URL::forceRootUrl('http://localhost/giftbloom/public');

        $this->get('http://localhost/products')
            ->assertOk()
            ->assertSee(
                'src="http://localhost/giftbloom/public/assets/image/roses.jpg"',
                false
            );
    }

    private function createProduct(): Product
    {
        $category = Category::create([
            'name' => 'Fallback Image Category',
            'slug' => 'fallback-image-category',
            'is_active' => true,
        ]);

        return Product::create([
            'name' => 'Product Without Images',
            'slug' => 'product-without-images',
            'category_id' => $category->id,
            'price' => 500,
            'sku' => 'PRODUCT-WITHOUT-IMAGES',
            'stock' => 5,
            'is_active' => true,
        ]);
    }
}
