<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->morphs('commentable');
            $table->dateTime('comment_date')->default(Carbon::now());

            $table->uuid('author');
            $table->foreign('author')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->text('body');

            $table->string('created_by');
            $table->string('updated_by')->nullable();

            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
