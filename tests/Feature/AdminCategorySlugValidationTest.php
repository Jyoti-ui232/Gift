<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategorySlugValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_category_with_duplicate_generated_slug_returns_validation_error(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $existing = $this->createCategory('Birthday Gifts', 'birthday-gifts');
        $categoryCount = Category::count();

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Birthday Gifts',
                'is_active' => 1,
            ])
            ->assertSessionHasErrors('slug');

        $this->assertSame($categoryCount, Category::count());
        $this->assertDatabaseHas('categories', [
            'id' => $existing->id,
            'slug' => 'birthday-gifts',
        ]);
    }

    public function test_updating_category_without_changing_its_slug_succeeds(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory('Flowers', 'flowers');

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Fresh Flowers',
                'slug' => $category->slug,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Fresh Flowers',
            'slug' => 'flowers',
        ]);
    }

    public function test_updating_category_to_another_categories_slug_returns_validation_error(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = $this->createCategory('Flowers', 'flowers');
        $otherCategory = $this->createCategory('Cakes', 'cakes');

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Flowers',
                'slug' => $otherCategory->slug,
                'is_active' => 1,
            ])
            ->assertSessionHasErrors('slug');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'slug' => 'flowers',
        ]);
        $this->assertDatabaseHas('categories', [
            'id' => $otherCategory->id,
            'slug' => 'cakes',
        ]);
    }

    private function createCategory(string $name, string $slug): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'is_active' => true,
        ]);
    }
}
