<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\UserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'customer')
            ->latest()
            ->paginate(10);
            
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'customer';
        
        User::create($data);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công');
    }

    public function show(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Không thể xem thông tin admin khác');
        }

        $orders = $user->orders()->latest()->paginate(5);
        $reviews = $user->reviews()->latest()->paginate(5);
        
        return view('admin.users.show', compact('user', 'orders', 'reviews'));
    }

    public function edit(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Không thể chỉnh sửa thông tin admin khác');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        if ($user->role === 'admin') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Không thể chỉnh sửa thông tin admin khác');
        }

        $data = $request->validated();
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        $user->update($data);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Thông tin người dùng đã được cập nhật');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Không thể xóa tài khoản admin');
        }

        $user->delete();
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa thành công');
    }

    public function updateStatus(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể thay đổi trạng thái của admin'
            ], 403);
        }

        $user->update([
            'is_active' => $request->status
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Trạng thái người dùng đã được cập nhật'
        ]);
    }
} 