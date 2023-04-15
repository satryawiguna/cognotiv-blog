<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('category');
            $table->foreign('category')
                ->references('id')
                ->on('blog_categories')
                ->onDelete('restrict');

            $table->uuid('author');
            $table->foreign('author')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->dateTime('published_date');
            $table->enum('status', ['published', 'pending', 'draft']);

            $table->string('title');
            $table->longText('content');

            $table->string('created_by');
            $table->string('updated_by')->nullable();

            $table->nullableTimestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
