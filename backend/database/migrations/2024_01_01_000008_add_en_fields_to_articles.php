<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnFieldsToArticles extends Migration
{
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('title_en', 255)->nullable()->after('title')->comment('英文标题');
            $table->text('description_en')->nullable()->after('description')->comment('英文描述');
            $table->longText('content_en')->nullable()->after('content')->comment('英文富文本内容');
        });
    }

    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en', 'content_en']);
        });
    }
}
