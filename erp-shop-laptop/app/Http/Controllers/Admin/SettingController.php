<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Http\Requests\Admin\SettingRequest;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'key')->all();
        
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(SettingRequest $request)
    {
        $data = $request->validated();
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        cache()->forget('settings');
        
        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Cài đặt đã được cập nhật');
    }
} 