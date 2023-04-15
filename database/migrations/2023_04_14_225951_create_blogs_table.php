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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('blog_category_id');
            $table->foreign('blog_category_id')
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
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
