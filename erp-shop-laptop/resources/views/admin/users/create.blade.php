@extends('admin.layouts.app')

@section('title', 'Thêm người dùng mới')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-medium text-gray-900">Thêm người dùng mới</h2>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" class="p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="form-label">Tên</label>
                <input type="text" name="name" id="name" class="form-input @error('name') border-red-500 @enderror" 
                    value="{{ old('name') }}" required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-input @error('email') border-red-500 @enderror" 
                    value="{{ old('email') }}" required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" name="phone" id="phone" class="form-input @error('phone') border-red-500 @enderror" 
                    value="{{ old('phone') }}">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" name="password" id="password" class="form-input @error('password') border-red-500 @enderror" 
                    required>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
            </div>
        </div>

        <div class="mt-6">
            <label for="address" class="form-label">Địa chỉ</label>
            <textarea name="address" id="address" rows="3" class="form-input @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
            @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" class="form-checkbox" value="1" checked>
                <span class="ml-2">Kích hoạt tài khoản</span>
            </label>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-times mr-2"></i>Hủy
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>Thêm mới
            </button>
        </div>
    </form>
</div>
@endsection 