<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_zh', 100)->comment('中文名称');
            $table->string('name_en', 100)->comment('英文名称');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父分类ID');
            $table->integer('sort_order')->default(0)->comment('排序');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
