<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Link the existing Birthday Gift Combo to the existing Birthday occasion.
 * Does not change products.category_id and inserts the pivot row only once.
 */
return new class extends Migration
{
    public function up(): void
    {
        $productId = DB::table('products')
            ->where('slug', 'birthday-gift-combo')
            ->value('id');

        $occasionId = DB::table('occasions')
            ->where('slug', 'birthday')
            ->value('id');

        if (! $productId || ! $occasionId) {
            return;
        }

        $alreadyLinked = DB::table('occasion_product')
            ->where('product_id', $productId)
            ->where('occasion_id', $occasionId)
            ->exists();

        if ($alreadyLinked) {
            return;
        }

        DB::table('occasion_product')->insert([
            'occasion_id' => $occasionId,
            'product_id' => $productId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $productId = DB::table('products')
            ->where('slug', 'birthday-gift-combo')
            ->value('id');

        $occasionId = DB::table('occasions')
            ->where('slug', 'birthday')
            ->value('id');

        if (! $productId || ! $occasionId) {
            return;
        }

        DB::table('occasion_product')
            ->where('product_id', $productId)
            ->where('occasion_id', $occasionId)
            ->delete();
    }
};
