<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToArticleCategories extends Migration
{
    public function up()
    {
        Schema::table('article_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id')->comment('父分类ID');
            $table->index('parent_id');
        });
    }

    public function down()
    {
        Schema::table('article_categories', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}
