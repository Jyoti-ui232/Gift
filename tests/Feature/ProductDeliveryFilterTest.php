<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDeliveryFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_listing_marks_only_same_day_products_as_eligible(): void
    {
        $category = $this->createCategory();
        $sameDayProduct = $this->createProduct($category, 'Same Day Flower', 'same-day-flower', 'same_day');
        $standardProduct = $this->createProduct($category, 'Standard Flower', 'standard-flower', 'standard');

        $response = $this->get(route('products.index'));
        $response->assertOk();

        $this->assertCardDeliveryType($response->getContent(), $sameDayProduct, 'same-day');
        $this->assertCardDeliveryType($response->getContent(), $standardProduct, 'standard');
    }

    public function test_shared_product_card_marks_delivery_type_from_product_data(): void
    {
        $category = $this->createCategory();
        $sameDayProduct = $this->createProduct($category, 'Same Day Cake', 'same-day-cake', 'same_day');
        $standardProduct = $this->createProduct($category, 'Standard Cake', 'standard-cake', 'standard');

        $response = $this->get(route('search', ['q' => 'Cake']));

        $response->assertOk();

        $this->assertCardDeliveryType($response->getContent(), $sameDayProduct, 'same-day');
        $this->assertCardDeliveryType($response->getContent(), $standardProduct, 'standard');
    }

    private function createCategory(): Category
    {
        return Category::create([
            'name' => 'Flowers',
            'slug' => 'flowers',
            'is_active' => true,
        ]);
    }

    private function createProduct(
        Category $category,
        string $name,
        string $slug,
        string $deliveryType
    ): Product {
        return Product::create([
            'name' => $name,
            'slug' => $slug,
            'category_id' => $category->id,
            'price' => 500,
            'sku' => strtoupper(str_replace('-', '', $slug)),
            'stock' => 5,
            'delivery_type' => $deliveryType,
            'is_active' => true,
        ]);
    }

    private function assertCardDeliveryType(string $html, Product $product, string $deliveryType): void
    {
        $productUrl = preg_quote(route('products.show', $product), '/');
        $deliveryType = preg_quote($deliveryType, '/');

        $this->assertMatchesRegularExpression(
            '/<div class="product-card"(?=[^>]*data-delivery="' . $deliveryType . '")'
                . '(?=[^>]*data-product-url="' . $productUrl . '")[^>]*>/s',
            $html
        );
    }
}
