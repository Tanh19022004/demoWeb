<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\PasswordUpdateRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => auth()->user()
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        
        $user->update($request->validated());
        
        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Thông tin cá nhân đã được cập nhật');
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = auth()->user();
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Mật khẩu đã được cập nhật');
    }
} 