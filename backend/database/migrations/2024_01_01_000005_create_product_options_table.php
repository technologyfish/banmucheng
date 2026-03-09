<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->comment('substrate | spec | thickness');
            $table->string('value_zh', 100)->comment('值（中文）');
            $table->string('value_en', 100)->comment('值（英文）');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('type');
        });

        // Seed default values
        $now = date('Y-m-d H:i:s');
        DB::table('product_options')->insert([
            // substrates
            ['type' => 'substrate', 'value_zh' => '包烧板', 'value_en' => 'Bao Shao Board', 'sort_order' => 10, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'substrate', 'value_zh' => '夹板',   'value_en' => 'Plywood',         'sort_order' => 20, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'substrate', 'value_zh' => '欧松板', 'value_en' => 'OSB',              'sort_order' => 30, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'substrate', 'value_zh' => '中纤板', 'value_en' => 'MDF',              'sort_order' => 40, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            // specs
            ['type' => 'spec', 'value_zh' => '1.22×2.44m',  'value_en' => '1.22×2.44m',  'sort_order' => 10, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'spec', 'value_zh' => '1.22×2.745m', 'value_en' => '1.22×2.745m', 'sort_order' => 20, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            // thicknesses
            ['type' => 'thickness', 'value_zh' => '1mm',  'value_en' => '1mm',  'sort_order' => 10, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'thickness', 'value_zh' => '5mm',  'value_en' => '5mm',  'sort_order' => 20, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'thickness', 'value_zh' => '8mm',  'value_en' => '8mm',  'sort_order' => 30, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'thickness', 'value_zh' => '9mm',  'value_en' => '9mm',  'sort_order' => 40, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'thickness', 'value_zh' => '12mm', 'value_en' => '12mm', 'sort_order' => 50, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'thickness', 'value_zh' => '15mm', 'value_en' => '15mm', 'sort_order' => 60, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'thickness', 'value_zh' => '18mm', 'value_en' => '18mm', 'sort_order' => 70, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['type' => 'thickness', 'value_zh' => '25mm', 'value_en' => '25mm', 'sort_order' => 80, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('product_options');
    }
}
