<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'maintenance_mode' => ['boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'site_name.required' => 'Vui lòng nhập tên website',
            'site_name.max' => 'Tên website không được vượt quá 255 ký tự',
            'site_description.max' => 'Mô tả website không được vượt quá 1000 ký tự',
            'contact_email.required' => 'Vui lòng nhập email liên hệ',
            'contact_email.email' => 'Email liên hệ không đúng định dạng',
            'contact_email.max' => 'Email liên hệ không được vượt quá 255 ký tự',
            'contact_phone.max' => 'Số điện thoại liên hệ không được vượt quá 20 ký tự',
            'contact_address.max' => 'Địa chỉ liên hệ không được vượt quá 255 ký tự',
            'social_facebook.url' => 'Link Facebook không đúng định dạng',
            'social_facebook.max' => 'Link Facebook không được vượt quá 255 ký tự',
            'social_twitter.url' => 'Link Twitter không đúng định dạng',
            'social_twitter.max' => 'Link Twitter không được vượt quá 255 ký tự',
            'social_instagram.url' => 'Link Instagram không đúng định dạng',
            'social_instagram.max' => 'Link Instagram không được vượt quá 255 ký tự',
            'meta_keywords.max' => 'Meta keywords không được vượt quá 255 ký tự',
            'meta_description.max' => 'Meta description không được vượt quá 1000 ký tự',
            'maintenance_message.max' => 'Thông báo bảo trì không được vượt quá 1000 ký tự'
        ];
    }
} 