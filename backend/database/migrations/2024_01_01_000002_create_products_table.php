<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable()->comment('分类ID');
            $table->string('name_zh', 200)->comment('产品中文名称');
            $table->string('name_en', 200)->comment('产品英文名称');
            $table->text('description_zh')->nullable()->comment('产品中文描述');
            $table->text('description_en')->nullable()->comment('产品英文描述');
            $table->json('substrates')->nullable()->comment('可选基材 JSON');
            $table->json('specs')->nullable()->comment('可选规格 JSON');
            $table->json('thicknesses')->nullable()->comment('可选厚度 JSON');
            $table->string('cover_image')->nullable()->comment('封面图片路径');
            $table->tinyInteger('status')->default(1)->comment('状态: 1上架 0下架');
            $table->integer('sort_order')->default(0)->comment('排序');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->index(['status', 'sort_order']);
            $table->index('category_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
