<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demo User
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        // =========================
        // CATEGORIES
        // =========================

        $categories = [
            ['name' => 'Flowers', 'slug' => 'flowers', 'sort_order' => 1],
            ['name' => 'Cakes', 'slug' => 'cakes', 'sort_order' => 2],
            ['name' => 'Gifts', 'slug' => 'gifts', 'sort_order' => 3],
            ['name' => 'Plants', 'slug' => 'plants', 'sort_order' => 4],
            ['name' => 'Personalized Gifts', 'slug' => 'personalized-gifts', 'sort_order' => 5],
            ['name' => 'Combos', 'slug' => 'combos', 'sort_order' => 6],
            ['name' => 'Chocolates', 'slug' => 'chocolates', 'sort_order' => 7],
            ['name' => 'Gift Hampers', 'slug' => 'gift-hampers', 'sort_order' => 8],
        ];

        $categoryModels = [];

        foreach ($categories as $category) {
            $categoryModels[$category['slug']] = Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                    'show_in_nav' => true,
                ]
            );
        }

        // =========================
        // SUB CATEGORIES
        // =========================

        $subCategories = [
            ['name' => 'Roses', 'slug' => 'roses', 'parent' => 'flowers'],
            ['name' => 'Bouquets', 'slug' => 'bouquets', 'parent' => 'flowers'],
            ['name' => 'Birthday Cakes', 'slug' => 'birthday-cakes', 'parent' => 'cakes'],
            ['name' => 'Anniversary Cakes', 'slug' => 'anniversary-cakes', 'parent' => 'cakes'],
            ['name' => 'Birthday Gifts', 'slug' => 'birthday-gifts', 'parent' => 'gifts'],
            ['name' => 'Anniversary Gifts', 'slug' => 'anniversary-gifts', 'parent' => 'gifts'],
        ];

        foreach ($subCategories as $subcategory) {
            Category::updateOrCreate(
                ['slug' => $subcategory['slug']],
                [
                    'name' => $subcategory['name'],
                    'parent_id' => $categoryModels[$subcategory['parent']]->id,
                    'sort_order' => 1,
                    'is_active' => true,
                    'show_in_nav' => true,
                ]
            );
        }

        // =========================
        // OCCASIONS
        // =========================

        $occasions = [
            ['name' => 'Birthday', 'slug' => 'birthday', 'sort_order' => 1],
            ['name' => 'Anniversary', 'slug' => 'anniversary', 'sort_order' => 2],
            ['name' => 'Love & Romance', 'slug' => 'love-romance', 'sort_order' => 3],
            ['name' => 'Congratulations', 'slug' => 'congratulations', 'sort_order' => 4],
            ['name' => 'Thank You', 'slug' => 'thank-you', 'sort_order' => 5],
            ['name' => 'Wedding', 'slug' => 'wedding', 'sort_order' => 6],
            ['name' => 'Baby', 'slug' => 'baby', 'sort_order' => 7],
            ['name' => 'Festivals', 'slug' => 'festivals', 'sort_order' => 8],
        ];

        foreach ($occasions as $occasion) {
            Occasion::updateOrCreate(
                ['slug' => $occasion['slug']],
                [
                    'name' => $occasion['name'],
                    'sort_order' => $occasion['sort_order'],
                ]
            );
        }

        // =========================
        // PRODUCTS
        // =========================

        $products = [
            [
                'name' => 'Red Rose Bouquet',
                'slug' => 'red-rose-bouquet',
                'category' => 'flowers',
                'price' => 999,
                'sale_price' => 799,
                'sku' => 'GB-FL-001',
                'stock' => 50,
                'delivery_type' => 'same_day',
                'rating' => 4.8,
                'reviews_count' => 124,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Pink Rose Bouquet',
                'slug' => 'pink-rose-bouquet',
                'category' => 'flowers',
                'price' => 899,
                'sale_price' => 699,
                'sku' => 'GB-FL-002',
                'stock' => 45,
                'delivery_type' => 'same_day',
                'rating' => 4.7,
                'reviews_count' => 98,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Elegant Mixed Flowers',
                'slug' => 'elegant-mixed-flowers',
                'category' => 'flowers',
                'price' => 1299,
                'sale_price' => 1099,
                'sku' => 'GB-FL-003',
                'stock' => 35,
                'delivery_type' => 'same_day',
                'rating' => 4.9,
                'reviews_count' => 76,
                'is_featured' => true,
                'is_bestseller' => false,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Chocolate Truffle Cake',
                'slug' => 'chocolate-truffle-cake',
                'category' => 'cakes',
                'price' => 899,
                'sale_price' => 749,
                'sku' => 'GB-CK-001',
                'stock' => 40,
                'delivery_type' => 'same_day',
                'rating' => 4.8,
                'reviews_count' => 186,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Red Velvet Cake',
                'slug' => 'red-velvet-cake',
                'category' => 'cakes',
                'price' => 999,
                'sale_price' => 849,
                'sku' => 'GB-CK-002',
                'stock' => 30,
                'delivery_type' => 'same_day',
                'rating' => 4.7,
                'reviews_count' => 143,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Butterscotch Cake',
                'slug' => 'butterscotch-cake',
                'category' => 'cakes',
                'price' => 899,
                'sale_price' => 749,
                'sku' => 'GB-CK-003',
                'stock' => 28,
                'delivery_type' => 'standard',
                'rating' => 4.6,
                'reviews_count' => 89,
                'is_featured' => false,
                'is_bestseller' => false,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Premium Chocolate Hamper',
                'slug' => 'premium-chocolate-hamper',
                'category' => 'gift-hampers',
                'price' => 1499,
                'sale_price' => 1199,
                'sku' => 'GB-GH-001',
                'stock' => 25,
                'delivery_type' => 'standard',
                'rating' => 4.9,
                'reviews_count' => 67,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Birthday Gift Combo',
                'slug' => 'birthday-gift-combo',
                'category' => 'combos',
                'price' => 1699,
                'sale_price' => 1399,
                'sku' => 'GB-CB-001',
                'stock' => 20,
                'delivery_type' => 'same_day',
                'rating' => 4.8,
                'reviews_count' => 91,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Personalized Photo Frame',
                'slug' => 'personalized-photo-frame',
                'category' => 'personalized-gifts',
                'price' => 799,
                'sale_price' => 599,
                'sku' => 'GB-PG-001',
                'stock' => 35,
                'delivery_type' => 'standard',
                'rating' => 4.7,
                'reviews_count' => 54,
                'is_featured' => true,
                'is_bestseller' => false,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Lucky Bamboo Plant',
                'slug' => 'lucky-bamboo-plant',
                'category' => 'plants',
                'price' => 699,
                'sale_price' => 549,
                'sku' => 'GB-PL-001',
                'stock' => 32,
                'delivery_type' => 'standard',
                'rating' => 4.6,
                'reviews_count' => 72,
                'is_featured' => false,
                'is_bestseller' => true,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Premium Chocolate Box',
                'slug' => 'premium-chocolate-box',
                'category' => 'chocolates',
                'price' => 1199,
                'sale_price' => 999,
                'sku' => 'GB-CH-001',
                'stock' => 45,
                'delivery_type' => 'standard',
                'rating' => 4.8,
                'reviews_count' => 115,
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new_arrival' => false,
            ],
        ];

        foreach ($products as $index => $data) {
            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'category_id' => $categoryModels[$data['category']]->id,
                    'short_description' => 'Beautiful gifting option for your loved ones.',
                    'description' => 'A beautiful GiftBloom product, perfect for birthdays, anniversaries and special celebrations.',
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'sku' => $data['sku'],
                    'stock' => $data['stock'],
                    'delivery_type' => $data['delivery_type'],
                    'is_active' => true,
                    'is_featured' => $data['is_featured'],
                    'is_bestseller' => $data['is_bestseller'],
                    'is_new_arrival' => $data['is_new_arrival'],
                    'rating' => $data['rating'],
                    'reviews_count' => $data['reviews_count'],
                    'sort_order' => $index + 1,
                ]
            );

            ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => 'Standard',
                ],
                [
                    'type' => 'size',
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'stock' => $data['stock'],
                    'sku' => $data['sku'] . '-STD',
                    'is_default' => true,
                ]
            );
        }

        $birthdayOccasion = Occasion::where('slug', 'birthday')->first();
        $birthdayCombo = Product::where('slug', 'birthday-gift-combo')->first();

        if ($birthdayOccasion && $birthdayCombo) {
            $birthdayCombo->occasions()->syncWithoutDetaching([$birthdayOccasion->id]);
        }

        // =========================
        // BANNERS
        // =========================

        $banners = [
            [
                'title' => 'Make Every Moment Special',
                'subtitle' => 'Beautiful flowers, cakes & gifts delivered with love.',
                'image' => 'banners/hero-1.jpg',
                'position' => 'hero',
                'button_text' => 'Shop Now',
                'link' => '/products',
                'sort_order' => 1,
            ],
            [
                'title' => 'Fresh Flowers for Every Occasion',
                'subtitle' => 'Send happiness with beautiful flowers.',
                'image' => 'banners/hero-2.jpg',
                'position' => 'hero',
                'button_text' => 'Explore Flowers',
                'link' => '/category/flowers',
                'sort_order' => 2,
            ],
            [
                'title' => 'Delicious Cakes',
                'subtitle' => 'Celebrate your special moments with delicious cakes.',
                'image' => 'banners/hero-3.jpg',
                'position' => 'hero',
                'button_text' => 'View Cakes',
                'link' => '/category/cakes',
                'sort_order' => 3,
            ],
            [
                'title' => 'Thoughtful Gifts',
                'subtitle' => 'Find something special for someone special.',
                'image' => 'banners/promo-1.jpg',
                'position' => 'promo',
                'button_text' => 'Shop Gifts',
                'link' => '/category/gifts',
                'sort_order' => 1,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                [
                    'title' => $banner['title'],
                    'position' => $banner['position'],
                ],
                [
                    'subtitle' => $banner['subtitle'],
                    'image' => $banner['image'],
                    'link' => $banner['link'],
                    'button_text' => $banner['button_text'],
                    'is_active' => true,
                    'sort_order' => $banner['sort_order'],
                ]
            );
        }

        $this->command->info('GiftBloom demo data seeded successfully!');
    }
}