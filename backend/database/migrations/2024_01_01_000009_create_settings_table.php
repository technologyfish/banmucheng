<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 100)->unique()->comment('配置键');
            $table->text('value')->nullable()->comment('配置值');
            $table->string('label', 100)->nullable()->comment('配置名称');
            $table->timestamps();
        });

        // 插入默认数据
        DB::table('settings')->insert([
            ['key' => 'footer_brand',     'value' => '板木诚',                              'label' => '底部品牌名称',  'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['key' => 'footer_copyright', 'value' => '© 2024 板木诚 版权所有',              'label' => '版权文案',      'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['key' => 'footer_address',   'value' => '广东省佛山市南海区丹灶镇明沙南路22号', 'label' => '联系地址',      'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['key' => 'footer_phone',     'value' => '13535717734',                          'label' => '联系电话',      'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ['key' => 'footer_email',     'value' => '995754135@qq.com',                     'label' => '联系邮箱',      'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
