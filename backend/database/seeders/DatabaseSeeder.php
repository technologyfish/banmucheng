<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create default admin account: admin / admin123
        DB::table('admin_users')->insertOrIgnore([
            'username'   => 'admin',
            'password'   => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create default categories
        $categories = [
            ['name_zh' => '压贴饰面板',  'name_en' => 'Laminated Panel',    'sort_order' => 1],
            ['name_zh' => '实木多层板',  'name_en' => 'Multilayer Plywood',  'sort_order' => 2],
            ['name_zh' => '中纤板',      'name_en' => 'MDF Board',           'sort_order' => 3],
            ['name_zh' => '欧松板',      'name_en' => 'OSB Board',           'sort_order' => 4],
            ['name_zh' => '碳酸钙板',    'name_en' => 'Calcium Silicate Board', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insertOrIgnore(array_merge($cat, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('Default admin created: admin / admin123');
        $this->command->info('Default categories created.');
    }
}
