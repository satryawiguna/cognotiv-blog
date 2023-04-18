<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();

            $table->unsignedBigInteger('blog_id');
            $table->foreign('blog_id')->references('id')->on('blogs')
                ->onDelete('restrict');

            $table->uuid('user_id', 36);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->unique(['blog_id', 'user_id']);

            $table->nullableTimestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
