<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->tinyInteger('rating')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::table('reviews')->whereNull('rating')->exists()) {
            throw new RuntimeException(
                'Cannot make reviews.rating non-null while reviews without ratings exist.'
            );
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->tinyInteger('rating')->nullable(false)->change();
        });
    }
};
