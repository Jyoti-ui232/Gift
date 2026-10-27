<?php

namespace Tests\Feature;

use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OccasionListingFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_occasion_page_renders_category_equivalent_filter_controls_and_sort_options(): void
    {
        $occasion = $this->createOccasion();

        $this->get(route('occasions.show', $occasion->slug))
            ->assertOk()
            ->assertSee('id="categoryFilterOpen"', false)
            ->assertSee('id="categoryFilterDrawer"', false)
            ->assertSee('name="min_price"', false)
            ->assertSee('name="max_price"', false)
            ->assertSee('value="featured"', false)
            ->assertSee('value="bestseller"', false)
            ->assertSee('value="new"', false)
            ->assertSee('Price: Low to High')
            ->assertSee('Price: High to Low')
            ->assertSee('Newest')
            ->assertSee('Top Rated')
            ->assertSee('Reset All');
    }

    public function test_occasion_price_range_uses_effective_price_and_keeps_occasion_scope(): void
    {
        $occasion = $this->createOccasion();
        $cheap = $this->createProduct('Cheap Sale', 200, 50);
        $inRange = $this->createProduct('In Range Sale', 100, 80);
        $invalidSale = $this->createProduct('Invalid Sale', 120, 150);
        $outsideOccasion = $this->createProduct('Not In Occasion', 75, null);
        $occasion->products()->attach([$cheap->id, $inRange->id, $invalidSale->id]);

        $response = $this->get(route('occasions.show', [
            'occasion' => $occasion->slug,
            'min_price' => 70,
            'max_price' => 85,
        ]));
        $response->assertOk();
        $this->assertSame($occasion->id, $response->viewData('occasion')->id);
        $response
            ->assertSee($inRange->name)
            ->assertDontSee($cheap->name)
            ->assertDontSee($invalidSale->name)
            ->assertDontSee($outsideOccasion->name);
    }

    public function test_occasion_collection_filters_apply_without_including_unrelated_products(): void
    {
        $occasion = $this->createOccasion();
        $featured = $this->createProduct('Featured Gift', 100, null, ['is_featured' => true]);
        $bestseller = $this->createProduct('Bestseller Gift', 100, null, ['is_bestseller' => true]);
        $newArrival = $this->createProduct('New Gift', 100, null, ['is_new_arrival' => true]);
        $ordinary = $this->createProduct('Ordinary Gift', 100, null);
        $unrelated = $this->createProduct('Unrelated Featured', 100, null, ['is_featured' => true]);
        $occasion->products()->attach([
            $featured->id,
            $bestseller->id,
            $newArrival->id,
            $ordinary->id,
        ]);

        foreach ([
            'featured' => [$featured, $bestseller, $newArrival, $ordinary, $unrelated],
            'bestseller' => [$bestseller, $featured, $newArrival, $ordinary, $unrelated],
            'new' => [$newArrival, $featured, $bestseller, $ordinary, $unrelated],
        ] as $filter => [$expected, $otherOne, $otherTwo, $ordinaryProduct, $unrelatedProduct]) {
            $this->get(route('occasions.show', $occasion->slug) . '?filter=' . $filter)
                ->assertOk()
                ->assertSee($expected->name)
                ->assertDontSee($otherOne->name)
                ->assertDontSee($otherTwo->name)
                ->assertDontSee($ordinaryProduct->name)
                ->assertDontSee($unrelatedProduct->name);
        }
    }

    public function test_occasion_page_supports_newest_and_rating_sorts(): void
    {
        $occasion = $this->createOccasion();
        $older = $this->createProduct('Older Rated Higher', 100, null, ['rating' => 5]);
        $newer = $this->createProduct('Newer Rated Lower', 100, null, ['rating' => 2]);
        $occasion->products()->attach([$older->id, $newer->id]);

        Product::whereKey($older->id)->update(['created_at' => now()->subDays(2)]);
        Product::whereKey($newer->id)->update(['created_at' => now()->subDay()]);

        $this->get(route('occasions.show', $occasion->slug) . '?sort=newest')
            ->assertOk()
            ->assertSeeInOrder([$newer->name, $older->name]);

        $this->get(route('occasions.show', $occasion->slug) . '?sort=rating')
            ->assertOk()
            ->assertSeeInOrder([$older->name, $newer->name]);
    }

    public function test_occasion_pagination_links_preserve_filter_and_sort_parameters(): void
    {
        $occasion = $this->createOccasion();
        $products = [];

        for ($number = 1; $number <= 17; $number++) {
            $products[] = $this->createProduct(
                'Pagination Gift ' . str_pad((string) $number, 2, '0', STR_PAD_LEFT),
                100 + $number,
                null,
                ['is_featured' => true]
            );
        }
        $occasion->products()->attach(collect($products)->pluck('id')->all());

        $response = $this->get(route('occasions.show', [
            'occasion' => $occasion->slug,
            'filter' => 'featured',
            'sort' => 'price_asc',
        ]));
        $response->assertOk();
        $response->assertSee('page=2', false)
            ->assertSee('filter=featured', false)
            ->assertSee('sort=price_asc', false);

        $this->get(route('occasions.show', [
            'occasion' => $occasion->slug,
            'filter' => 'featured',
            'sort' => 'price_asc',
            'page' => 2,
        ]))
            ->assertOk()
            ->assertSee('Pagination Gift 17');
    }

    private function createOccasion(): Occasion
    {
        return Occasion::create([
            'name' => 'Test Occasion',
            'slug' => 'test-occasion',
            'description' => 'Occasion description',
            'is_active' => true,
        ]);
    }

    private function createProduct(string $name, float $price, ?float $salePrice, array $attributes = []): Product
    {
        static $productNumber = 0;

        $category = \App\Models\Category::firstOrCreate(
            ['slug' => 'occasion-filter-category'],
            ['name' => 'Occasion Filter Category', 'is_active' => true]
        );

        return Product::create(array_merge([
            'name' => $name,
            'slug' => 'occasion-product-' . ++$productNumber,
            'category_id' => $category->id,
            'price' => $price,
            'sale_price' => $salePrice,
            'sku' => 'OCCASION-PRODUCT-' . $productNumber,
            'stock' => 5,
            'sort_order' => 0,
            'rating' => 0,
            'is_active' => true,
            'is_featured' => false,
            'is_bestseller' => false,
            'is_new_arrival' => false,
        ], $attributes));
    }
}
