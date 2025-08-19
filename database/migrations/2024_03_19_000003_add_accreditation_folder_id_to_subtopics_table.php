<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subtopics', function (Blueprint $table) {
            $table->foreignId('accreditation_folder_id')->nullable()->constrained('accreditation_folders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('subtopics', function (Blueprint $table) {
            $table->dropForeign(['accreditation_folder_id']);
            $table->dropColumn('accreditation_folder_id');
        });
    }
}; 