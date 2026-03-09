<?php

use Illuminate\Database\Migrations\Migration;

class AddContactSettings extends Migration
{
    public function up()
    {
        $now = \Carbon\Carbon::now();

        $rows = [
            // 电话
            ['key' => 'contact_tel',          'value' => '+86 13535717734',           'label' => '联系电话'],
            ['key' => 'contact_tel_en',        'value' => '+86 13535717734',           'label' => '联系电话-英文'],
            // WhatsApp
            ['key' => 'contact_whatsapp',      'value' => '+86 13535717734',           'label' => 'WhatsApp'],
            ['key' => 'contact_whatsapp_en',   'value' => '+86 13535717734',           'label' => 'WhatsApp-英文'],
            // 微信
            ['key' => 'contact_wechat',        'value' => '13535717734',               'label' => '微信'],
            ['key' => 'contact_wechat_en',     'value' => '13535717734',               'label' => '微信-英文'],
            // 邮箱
            ['key' => 'contact_email',         'value' => '995754135@qq.com',          'label' => '联系邮箱'],
            ['key' => 'contact_email_en',      'value' => '995754135@qq.com',          'label' => '联系邮箱-英文'],
            // 地址
            ['key' => 'contact_address',       'value' => '广东省佛山市南海区丹灶镇明沙南路22号', 'label' => '联系地址'],
            ['key' => 'contact_address_en',    'value' => 'No. 22, Mingsha South Road, Danzao Town, Nanhai District, Foshan City, Guangdong Province, China', 'label' => '联系地址-英文'],
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
            'contact_tel', 'contact_tel_en',
            'contact_whatsapp', 'contact_whatsapp_en',
            'contact_wechat', 'contact_wechat_en',
            'contact_email', 'contact_email_en',
            'contact_address', 'contact_address_en',
        ])->delete();
    }
}
