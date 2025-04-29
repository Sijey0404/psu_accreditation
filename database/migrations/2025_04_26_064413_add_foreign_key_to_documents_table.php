<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Add foreign key constraint
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['folder_id']);
        });
    }
    
};
