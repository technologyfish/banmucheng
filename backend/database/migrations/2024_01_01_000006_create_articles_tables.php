<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTables extends Migration
{
    public function up()
    {
        // Article categories
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('分类名称');
            $table->string('slug', 100)->unique()->comment('别名');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Articles
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable()->comment('分类ID');
            $table->string('title', 255)->comment('文章标题');
            $table->text('description')->nullable()->comment('文章描述');
            $table->string('cover_image', 255)->nullable()->comment('封面图片');
            $table->longText('content')->nullable()->comment('富文本内容');
            $table->timestamp('published_at')->nullable()->comment('发布时间');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('category_id');
            $table->index('published_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_categories');
    }
}
