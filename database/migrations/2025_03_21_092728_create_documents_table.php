<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            $table->string('category');
            $table->string('original_link')->nullable(); // New column for external links
            $table->enum('status', ['pending', 'approved', 'rejected', 'locked'])->default('pending');
            $table->text('rejection_reason')->default(''); // Ensure it's never NULL
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('file_type')->nullable(); // New column to store file type
            $table->timestamp('approved_at')->nullable(); // Optional timestamp for approval
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};