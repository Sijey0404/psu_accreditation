<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->text('strengths');
            $table->text('improvements');
            $table->text('recommendations');
            $table->integer('rating');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
}; 