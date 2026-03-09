<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * 公开接口：返回所有配置（key => value）
     */
    public function publicIndex()
    {
        return response()->json(Setting::allAsMap());
    }

    /**
     * 管理员接口：返回完整配置列表（含 label）
     */
    public function adminIndex()
    {
        $settings = Setting::orderBy('id')->get(['id', 'key', 'label', 'value']);
        return response()->json($settings);
    }

    /**
     * 管理员接口：批量保存配置
     * POST body: { "footer_brand": "板木诚", "footer_phone": "...", ... }
     */
    public function adminUpdate(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return response()->json(['message' => '保存成功']);
    }
}
