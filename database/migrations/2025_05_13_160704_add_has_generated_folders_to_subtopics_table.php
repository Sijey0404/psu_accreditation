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
        Schema::table('subtopics', function (Blueprint $table) {
            $table->boolean('has_generated_folders')->default(false)->after('name');
            // You can change `after('name')` to any existing column if needed, or remove it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subtopics', function (Blueprint $table) {
            $table->dropColumn('has_generated_folders');
        });
    }
};
