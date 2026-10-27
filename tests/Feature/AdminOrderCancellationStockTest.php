<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class AdminOrderCancellationStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancelling_an_order_restores_product_stock(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3);

        $this->updateStatusAsAdmin($admin, $order, 'cancelled')
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame(10, (int) $product->fresh()->stock);
        $this->assertNotNull($order->fresh()->inventory_restored_at);
    }

    public function test_admin_can_cancel_orders_from_each_allowed_status(): void
    {
        foreach (['pending', 'confirmed', 'processing'] as $status) {
            [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3, status: $status);

            $this->updateStatusAsAdmin($admin, $order, 'cancelled')
                ->assertRedirect(route('admin.orders.show', $order));

            $this->assertSame('cancelled', $order->fresh()->status);
            $this->assertSame(10, (int) $product->fresh()->stock);
            $this->assertNotNull($order->fresh()->inventory_restored_at);
        }
    }

    public function test_admin_cannot_cancel_orders_from_shipped_delivered_or_cancelled_statuses(): void
    {
        foreach (['shipped', 'delivered', 'cancelled'] as $status) {
            [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3, status: $status);

            $this->updateStatusAsAdmin($admin, $order, 'cancelled')
                ->assertStatus(409);

            $this->assertSame($status, $order->fresh()->status);
            $this->assertSame(7, (int) $product->fresh()->stock);
            $this->assertNull($order->fresh()->inventory_restored_at);
        }
    }

    public function test_cancelling_an_order_restores_variant_stock(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Large',
            'type' => 'size',
            'price' => 100,
            'stock' => 4,
        ]);
        $order->items()->first()->update([
            'product_variant_id' => $variant->id,
            'variant_name' => $variant->name,
        ]);

        $this->updateStatusAsAdmin($admin, $order, 'cancelled');

        $this->assertSame(10, (int) $product->fresh()->stock);
        $this->assertSame(7, (int) $variant->fresh()->stock);
    }

    public function test_repeated_cancellation_restores_stock_only_once(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3);

        $this->updateStatusAsAdmin($admin, $order, 'cancelled');
        $this->updateStatusAsAdmin($admin, $order, 'cancelled');

        $this->assertSame(10, (int) $product->fresh()->stock);
    }

    public function test_non_cancelled_status_changes_do_not_restore_stock(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3, status: 'processing');

        $this->updateStatusAsAdmin($admin, $order, 'shipped');

        $this->assertSame(7, (int) $product->fresh()->stock);
        $this->assertNull($order->fresh()->inventory_restored_at);
    }

    public function test_reopening_a_cancelled_order_does_not_allow_stock_to_be_restored_twice(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3);

        $this->updateStatusAsAdmin($admin, $order, 'cancelled');
        $this->updateStatusAsAdmin($admin, $order, 'processing');
        $this->updateStatusAsAdmin($admin, $order, 'cancelled');

        $this->assertSame(10, (int) $product->fresh()->stock);
    }

    public function test_previously_cancelled_order_remains_handled_after_reopening(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 10, quantity: 3, status: 'cancelled');
        $order->forceFill(['inventory_restoration_eligible' => false])->save();

        $this->updateStatusAsAdmin($admin, $order, 'processing');
        $this->updateStatusAsAdmin($admin, $order, 'cancelled');

        $this->assertSame(10, (int) $product->fresh()->stock);
        $this->assertNull($order->fresh()->inventory_restored_at);
    }

    public function test_customer_can_cancel_own_pending_order_and_sees_cancel_controls(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3, status: 'pending');
        $customer = User::factory()->create();
        $order->forceFill(['user_id' => $customer->id])->save();

        $this->actingAs($customer)
            ->get(route('account.orders'))
            ->assertOk()
            ->assertSee('Cancel Order')
            ->assertSee(route('account.orders.cancel', $order))
            ->assertSee('return confirm(');

        $this->actingAs($customer)
            ->get(route('account.orders.detail', $order))
            ->assertOk()
            ->assertSee('Cancel Order')
            ->assertSee(route('account.orders.cancel', $order))
            ->assertSee('return confirm(');

        $this->actingAs($customer)
            ->post(route('account.orders.cancel', $order))
            ->assertRedirect(route('account.orders.detail', $order))
            ->assertSessionHas('success', 'Order cancelled successfully.');

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(10, (int) $product->fresh()->stock);
        $this->assertNotNull($order->fresh()->inventory_restored_at);
    }

    public function test_customer_cannot_cancel_another_customers_order(): void
    {
        [, $product, $order] = $this->createOrder(stock: 7, quantity: 3, status: 'pending');
        $owner = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $order->forceFill(['user_id' => $owner->id])->save();

        $this->actingAs($otherCustomer)
            ->post(route('account.orders.cancel', $order))
            ->assertForbidden();

        $this->assertSame('pending', $order->fresh()->status);
        $this->assertSame(7, (int) $product->fresh()->stock);
    }

    public function test_customer_cannot_cancel_an_order_that_is_not_pending(): void
    {
        [, $product, $order] = $this->createOrder(stock: 7, quantity: 3, status: 'processing');
        $customer = User::factory()->create();
        $order->forceFill(['user_id' => $customer->id])->save();

        $this->actingAs($customer)
            ->post(route('account.orders.cancel', $order))
            ->assertStatus(409);

        $this->assertSame('processing', $order->fresh()->status);
        $this->assertSame(7, (int) $product->fresh()->stock);
    }

    public function test_customer_and_admin_cancellation_do_not_restore_stock_twice(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3, status: 'pending');
        $customer = User::factory()->create();
        $order->forceFill(['user_id' => $customer->id])->save();

        $this->actingAs($customer)->post(route('account.orders.cancel', $order));
        $this->updateStatusAsAdmin($admin, $order, 'cancelled');

        $this->assertSame(10, (int) $product->fresh()->stock);
        $this->assertNotNull($order->fresh()->inventory_restored_at);
    }

    public function test_customer_cancellation_does_not_restore_ineligible_historical_order(): void
    {
        [, $product, $order] = $this->createOrder(stock: 7, quantity: 3, status: 'pending');
        $customer = User::factory()->create();
        $order->forceFill([
            'user_id' => $customer->id,
            'inventory_restoration_eligible' => false,
        ])->save();

        $this->actingAs($customer)
            ->post(route('account.orders.cancel', $order))
            ->assertRedirect(route('account.orders.detail', $order));

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(7, (int) $product->fresh()->stock);
        $this->assertNull($order->fresh()->inventory_restored_at);
    }

    public function test_cancellation_fails_safely_when_inventory_restoration_column_is_missing(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3, status: 'cancelled');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('inventory_restored_at');
        });

        $this->updateStatusAsAdmin($admin, $order, 'cancelled')
            ->assertServiceUnavailable()
            ->assertSee('inventory restoration migration');

        $this->assertSame(7, (int) $product->fresh()->stock);
        $this->assertSame('cancelled', $order->fresh()->status);
    }

    public function test_cancellation_fails_safely_when_eligibility_column_is_missing(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3);
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('inventory_restoration_eligible');
        });

        $this->updateStatusAsAdmin($admin, $order, 'cancelled')
            ->assertServiceUnavailable()
            ->assertSee('inventory restoration migration');

        $this->assertSame(7, (int) $product->fresh()->stock);
        $this->assertSame('confirmed', $order->fresh()->status);
    }

    public function test_cancellation_fails_safely_when_order_item_variant_column_is_missing(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3);
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
        });

        $this->updateStatusAsAdmin($admin, $order, 'cancelled')
            ->assertServiceUnavailable()
            ->assertSee('inventory restoration migration');

        $this->assertSame(7, (int) $product->fresh()->stock);
        $this->assertSame('confirmed', $order->fresh()->status);
    }

    public function test_migration_marks_legacy_cancelled_orders_without_guessing_variant_ids(): void
    {
        $connectionName = 'inventory_restoration_migration_test';
        $originalDefault = config('database.default');
        config([
            "database.connections.{$connectionName}" => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
                'busy_timeout' => null,
                'journal_mode' => null,
                'synchronous' => null,
                'transaction_mode' => 'DEFERRED',
            ],
            'database.default' => $connectionName,
        ]);
        DB::purge($connectionName);

        try {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('status');
            });
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('name');
            });
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('variant_name')->nullable();
            });

            DB::table('orders')->insert([
                ['id' => 1, 'status' => 'cancelled'],
                ['id' => 2, 'status' => 'processing'],
            ]);
            DB::table('product_variants')->insert([
                'id' => 1,
                'product_id' => 10,
                'name' => 'Large',
            ]);
            DB::table('order_items')->insert([
                'id' => 1,
                'product_id' => 10,
                'variant_name' => 'Large',
            ]);

            $migration = require database_path(
                'migrations/2026_10_26_174000_add_inventory_restoration_references.php'
            );
            $migration->up();

            $this->assertNull(DB::table('orders')->where('id', 1)->value('inventory_restored_at'));
            $this->assertFalse((bool) DB::table('orders')->where('id', 1)->value('inventory_restoration_eligible'));
            $this->assertSame('cancelled', DB::table('orders')->where('id', 1)->value('status'));
            $this->assertNull(DB::table('orders')->where('id', 2)->value('inventory_restored_at'));
            $this->assertSame('processing', DB::table('orders')->where('id', 2)->value('status'));
            $this->assertNull(DB::table('order_items')->where('id', 1)->value('product_variant_id'));
        } finally {
            config(['database.default' => $originalDefault]);
            DB::purge($connectionName);
            config(["database.connections.{$connectionName}" => null]);
        }
    }

    public function test_checkout_variant_reference_is_restored_to_the_exact_variant(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Variant Category', 'slug' => 'variant-category']);
        $product = Product::create([
            'name' => 'Variant Product',
            'slug' => 'variant-product',
            'category_id' => $category->id,
            'price' => 100,
            'sku' => 'VARIANT-PRODUCT',
            'stock' => 10,
            'is_active' => true,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Large',
            'type' => 'size',
            'price' => 100,
            'stock' => 6,
        ]);
        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)->post(route('checkout.store'), [
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '9876543210',
            'recipient_address' => '123 Test Street',
            'recipient_city' => 'Delhi',
            'recipient_state' => 'Delhi',
            'recipient_pincode' => '110001',
            'delivery_date' => now()->addDay()->toDateString(),
            'payment_method' => 'cod',
        ])->assertRedirect();

        $order = Order::firstOrFail();
        $this->assertTrue($order->inventory_restoration_eligible);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
        ]);
        $this->assertSame(8, (int) $product->fresh()->stock);
        $this->assertSame(4, (int) $variant->fresh()->stock);

        $this->updateStatusAsAdmin($admin, $order, 'cancelled');

        $this->assertSame(10, (int) $product->fresh()->stock);
        $this->assertSame(6, (int) $variant->fresh()->stock);
    }

    public function test_cancellation_handles_deleted_products_and_variants_without_recreating_them(): void
    {
        [$admin, $product, $order] = $this->createOrder(stock: 7, quantity: 3);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Large',
            'type' => 'size',
            'price' => 100,
            'stock' => 4,
        ]);
        $order->items()->first()->update([
            'product_variant_id' => $variant->id,
            'variant_name' => $variant->name,
        ]);
        $product->delete();

        $this->updateStatusAsAdmin($admin, $order, 'cancelled')
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('product_variants', ['id' => $variant->id]);
        $this->assertNotNull($order->fresh()->inventory_restored_at);
    }

    private function updateStatusAsAdmin(User $admin, Order $order, string $status)
    {
        return $this->actingAs($admin)->patch(route('admin.orders.status', $order), [
            'status' => $status,
        ]);
    }

    private function createOrder(int $stock, int $quantity, string $status = 'confirmed'): array
    {
        static $fixtureNumber = 0;
        $fixtureNumber++;

        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create([
            'name' => 'Test Category ' . $fixtureNumber,
            'slug' => 'test-category-' . $fixtureNumber,
        ]);
        $product = Product::create([
            'name' => 'Test Product ' . $fixtureNumber,
            'slug' => 'test-product-' . $fixtureNumber,
            'category_id' => $category->id,
            'price' => 100,
            'sku' => 'TEST-PRODUCT-' . $fixtureNumber,
            'stock' => $stock,
            'is_active' => true,
        ]);
        $order = Order::create([
            'order_number' => 'GB' . now()->format('Ymd') . str_pad((string) (Order::count() + 1), 4, '0', STR_PAD_LEFT),
            'status' => $status,
            'inventory_restoration_eligible' => true,
            'subtotal' => 100 * $quantity,
            'total' => 100 * $quantity,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '9876543210',
            'recipient_address' => '123 Test Street',
            'recipient_city' => 'Delhi',
            'recipient_state' => 'Delhi',
            'recipient_pincode' => '110001',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 100,
            'quantity' => $quantity,
            'total' => 100 * $quantity,
        ]);

        return [$admin, $product, $order];
    }
}
