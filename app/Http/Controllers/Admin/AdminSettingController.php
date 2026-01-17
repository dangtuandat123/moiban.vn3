<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * AdminSettingController - Quản lý cài đặt hệ thống
 */
class AdminSettingController extends Controller
{
    /**
     * Trang settings
     */
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Cập nhật settings
     */
    public function update(Request $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // Clear cache
        Cache::forget('settings');

        return back()->with('success', 'Đã lưu cài đặt');
    }

    /**
     * Tạo setting mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings,key|regex:/^[a-z_]+$/',
            'value' => 'nullable|string',
            'type' => 'required|in:string,integer,boolean,json',
            'group' => 'required|string|max:50',
        ]);

        Setting::create([
            'key' => $request->key,
            'value' => $request->value,
            'type' => $request->type,
            'group' => $request->group,
        ]);

        Cache::forget('settings');

        return back()->with('success', 'Đã tạo setting mới');
    }

    /**
     * Xóa setting
     */
    public function destroy(string $key)
    {
        Setting::where('key', $key)->delete();
        Cache::forget('settings');

        return back()->with('success', 'Đã xóa setting');
    }
}
