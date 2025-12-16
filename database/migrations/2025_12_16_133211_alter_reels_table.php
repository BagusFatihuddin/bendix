<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('reels', function (Blueprint $table) {

        if (!Schema::hasColumn('reels', 'public_id')) {
            $table->string('public_id')->after('id');
        }

        if (!Schema::hasColumn('reels', 'title')) {
            $table->string('title')->nullable()->after('public_id');
        }

        if (!Schema::hasColumn('reels', 'is_active')) {
            $table->boolean('is_active')->default(true)->after('title');
        }

    });
}

public function down(): void
{
    Schema::table('reels', function (Blueprint $table) {
        $table->dropColumn(['public_id', 'title', 'is_active']);
    });
}



};
