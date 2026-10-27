<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemProductDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_a_product_preserves_its_order_item_snapshot(): void
    {
        $category = $this->createCategory();
        $product = $this->createProduct($category);
        [$order, $item] = $this->createOrderWithItem($product);

        $this->deleteAsAdmin(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
        $this->assertHistoricalSnapshotPreserved($item->id, $order->id);
    }

    public function test_deleting_a_category_preserves_order_items_for_cascaded_products(): void
    {
        $category = $this->createCategory();
        $product = $this->createProduct($category);
        [$order, $item] = $this->createOrderWithItem($product);

        $this->deleteAsAdmin(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseHas('orders', ['id' => $order->id]);
        $this->assertHistoricalSnapshotPreserved($item->id, $order->id);
    }

    public function test_deleting_an_order_still_deletes_its_order_items(): void
    {
        $category = $this->createCategory();
        $product = $this->createProduct($category);
        [$order, $item] = $this->createOrderWithItem($product);

        $order->delete();

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('order_items', ['id' => $item->id]);
    }

    private function deleteAsAdmin(string $url)
    {
        $admin = User::factory()->create(['is_admin' => true]);

        return $this->actingAs($admin)->delete($url);
    }

    private function createCategory(): Category
    {
        return Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);
    }

    private function createProduct(Category $category): Product
    {
        return Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'category_id' => $category->id,
            'price' => 1200,
            'sku' => 'TEST-PRODUCT',
            'stock' => 10,
            'is_active' => true,
        ]);
    }

    private function createOrderWithItem(Product $product): array
    {
        $order = Order::create([
            'order_number' => 'GB' . now()->format('Ymd') . '0001',
            'status' => 'confirmed',
            'subtotal' => 2400,
            'discount' => 100,
            'delivery_charge' => 49,
            'total' => 2349,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '9876543210',
            'recipient_address' => '123 Test Street',
            'recipient_city' => 'Delhi',
            'recipient_state' => 'Delhi',
            'recipient_pincode' => '110001',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => 'Historical Product Name',
            'variant_name' => 'Historical Large',
            'price' => 1200,
            'quantity' => 2,
            'total' => 2400,
            'add_ons' => [
                ['id' => 7, 'name' => 'Gift Wrap', 'price' => 50],
            ],
        ]);

        return [$order, $item];
    }

    private function assertHistoricalSnapshotPreserved(int $itemId, int $orderId): void
    {
        $item = OrderItem::findOrFail($itemId);

        $this->assertNull($item->product_id);
        $this->assertSame($orderId, $item->order_id);
        $this->assertSame('Historical Product Name', $item->product_name);
        $this->assertSame('Historical Large', $item->variant_name);
        $this->assertSame(1200.0, (float) $item->price);
        $this->assertSame(2, $item->quantity);
        $this->assertSame(2400.0, (float) $item->total);
        $this->assertSame([
            ['id' => 7, 'name' => 'Gift Wrap', 'price' => 50],
        ], $item->add_ons);
    }
}
