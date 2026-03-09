<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'label'];

    /**
     * 获取所有配置，返回 key => value 的关联数组
     */
    public static function allAsMap(): array
    {
        return self::all()->pluck('value', 'key')->toArray();
    }
}
