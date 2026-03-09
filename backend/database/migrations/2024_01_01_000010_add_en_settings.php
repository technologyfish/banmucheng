<?php

use Illuminate\Database\Migrations\Migration;

class AddEnSettings extends Migration
{
    public function up()
    {
        $now = \Carbon\Carbon::now();

        $rows = [
            ['key' => 'footer_brand_en',     'value' => 'Banmucheng',           'label' => '底部品牌名称-英文'],
            ['key' => 'footer_copyright_en', 'value' => '© 2024 Banmucheng. All rights reserved.', 'label' => '版权文案-英文'],
            ['key' => 'footer_address_en',   'value' => 'No.22 Mingsha South Road, Danzao Town, Nanhai District, Foshan, Guangdong', 'label' => '联系地址-英文'],
            ['key' => 'footer_phone_en',     'value' => '',                      'label' => '联系电话-英文'],
            ['key' => 'footer_email_en',     'value' => '',                      'label' => '联系邮箱-英文'],
        ];

        foreach ($rows as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        DB::table('settings')->insert($rows);
    }

    public function down()
    {
        DB::table('settings')->whereIn('key', [
            'footer_brand_en', 'footer_copyright_en', 'footer_address_en',
            'footer_phone_en', 'footer_email_en',
        ])->delete();
    }
}
