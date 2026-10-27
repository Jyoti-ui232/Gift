<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fix banner href links that use the old /category/{slug} URL pattern.
 * The correct URLs use the CategoryController's route: /{slug}
 * This migration only updates banners where the link starts with /category/
 * so it is safe to run against any existing data.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Map old broken link → correct link
        $fixes = [
            '/category/flowers' => '/flowers',
            '/category/cakes'   => '/cakes',
            '/category/gifts'   => '/gifts',
        ];

        foreach ($fixes as $old => $new) {
            DB::table('banners')
                ->where('link', $old)
                ->update(['link' => $new, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // Restore old (broken) links if rolled back
        $restore = [
            '/flowers' => '/category/flowers',
            '/cakes'   => '/category/cakes',
            '/gifts'   => '/category/gifts',
        ];

        foreach ($restore as $current => $original) {
            DB::table('banners')
                ->where('link', $current)
                ->update(['link' => $original, 'updated_at' => now()]);
        }
    }
};
