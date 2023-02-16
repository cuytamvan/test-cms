<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_category_id');
            $table->string('title');
            $table->string('slug');
            $table->string('banner', 50);
            $table->text('content');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
    }
};
