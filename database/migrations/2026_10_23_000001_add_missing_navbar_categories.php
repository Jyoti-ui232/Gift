<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Add missing navbar categories: Lifestyle, Hatke, LUXE, Balloons.
 * Uses insertOrIgnore-style logic (checks by slug before inserting)
 * so re-running is safe and idempotent.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $missing = [
            [
                'name'        => 'Lifestyle',
                'slug'        => 'lifestyle',
                'parent_id'   => null,
                'description' => 'Lifestyle gifts and products for everyday living.',
                'sort_order'  => 90,
                'is_active'   => 1,
                'show_in_nav' => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Hatke',
                'slug'        => 'hatke',
                'parent_id'   => null,
                'description' => 'Quirky, unique and out-of-the-box gifts.',
                'sort_order'  => 100,
                'is_active'   => 1,
                'show_in_nav' => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'LUXE',
                'slug'        => 'luxe',
                'parent_id'   => null,
                'description' => 'Luxury gifting collection for the finest moments.',
                'sort_order'  => 110,
                'is_active'   => 1,
                'show_in_nav' => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Balloons',
                'slug'        => 'balloons',
                'parent_id'   => null,
                'description' => 'Beautiful balloon arrangements for celebrations.',
                'sort_order'  => 120,
                'is_active'   => 1,
                'show_in_nav' => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        foreach ($missing as $cat) {
            $exists = DB::table('categories')->where('slug', $cat['slug'])->exists();
            if (! $exists) {
                DB::table('categories')->insert($cat);
            }
        }
    }

    public function down(): void
    {
        // Only remove what this migration created (by slug), leaving all other data intact.
        DB::table('categories')
            ->whereIn('slug', ['lifestyle', 'hatke', 'luxe', 'balloons'])
            ->delete();
    }
};
