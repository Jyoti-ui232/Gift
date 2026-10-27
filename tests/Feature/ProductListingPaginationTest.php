<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductListingPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_listing_renders_pagination_links(): void
    {
        $category = $this->createCategory('Flowers', 'flowers');
        $this->createProducts($category, 'Flower', 13);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('page=2');
    }

    public function test_page_two_shows_products_not_on_page_one(): void
    {
        $category = $this->createCategory('Flowers', 'flowers');
        $this->createProducts($category, 'Flower', 13);

        $this->get(route('products.index', ['sort' => 'price_low']))
            ->assertOk()
            ->assertSee('Flower Gift 01')
            ->assertDontSee('Flower Gift 13');

        $this->get(route('products.index', ['sort' => 'price_low', 'page' => 2]))
            ->assertOk()
            ->assertSee('Flower Gift 13')
            ->assertDontSee('Flower Gift 01');
    }

    public function test_page_links_preserve_search_category_and_sort_parameters(): void
    {
        $flowers = $this->createCategory('Flowers', 'flowers');
        $cakes = $this->createCategory('Cakes', 'cakes');
        $this->createProducts($flowers, 'Rose', 13);
        $this->createProducts($cakes, 'Cake', 1);

        $response = $this->get(route('products.index', [
            'search' => 'Rose',
            'category' => 'flowers',
            'sort' => 'price_low',
        ]));
        $response->assertOk();

        preg_match_all('/<a\\b[^>]*href="([^"]+)"[^>]*>/i', $response->getContent(), $matches);
        $pageTwoUrl = null;

        foreach ($matches[1] as $href) {
            $href = html_entity_decode($href, ENT_QUOTES);
            parse_str(parse_url($href, PHP_URL_QUERY) ?? '', $query);

            if (($query['page'] ?? null) === '2') {
                $pageTwoUrl = $href;
                $this->assertSame('Rose', $query['search'] ?? null);
                $this->assertSame('flowers', $query['category'] ?? null);
                $this->assertSame('price_low', $query['sort'] ?? null);
                break;
            }
        }

        $this->assertNotNull($pageTwoUrl, 'The pagination links should include page 2.');

        $path = parse_url($pageTwoUrl, PHP_URL_PATH);
        $query = parse_url($pageTwoUrl, PHP_URL_QUERY);
        $pageTwoResponse = $this->get($path . ($query ? '?' . $query : ''));

        $pageTwoResponse->assertOk()
            ->assertSee('Rose Gift 13')
            ->assertDontSee('Cake Gift 01');
    }

    private function createCategory(string $name, string $slug): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'is_active' => true,
        ]);
    }

    private function createProducts(Category $category, string $prefix, int $count): void
    {
        for ($number = 1; $number <= $count; $number++) {
            $suffix = str_pad((string) $number, 2, '0', STR_PAD_LEFT);

            Product::create([
                'name' => $prefix . ' Gift ' . $suffix,
                'slug' => strtolower($prefix) . '-gift-' . $suffix,
                'category_id' => $category->id,
                'price' => 100 + $number,
                'sku' => strtoupper($prefix) . '-GIFT-' . $suffix,
                'stock' => 5,
                'is_active' => true,
            ]);
        }
    }
}
