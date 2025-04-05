<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('subtopic_id')->after('id')->nullable();
            $table->foreign('subtopic_id')->references('id')->on('subtopics')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['subtopic_id']);
            $table->dropColumn('subtopic_id');
        });
    }
};
