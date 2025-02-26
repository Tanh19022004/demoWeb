@extends('admin.layouts.app')

@section('title', 'Sửa danh mục')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-medium text-gray-900">Sửa danh mục</h2>
        </div>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Tên danh mục -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Tên danh mục</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" 
                        class="mt-1 form-input block w-full rounded-md" required
                        placeholder="Nhập tên danh mục">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" 
                        class="mt-1 form-input block w-full rounded-md"
                        placeholder="Nhập slug hoặc để trống để tự động tạo">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Danh mục cha -->
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700">Danh mục cha</label>
                    <select name="parent_id" id="parent_id" class="mt-1 form-select block w-full rounded-md">
                        <option value="">Không có</option>
                        @foreach($categories as $parent)
                            @if($parent->id !== $category->id)
                                <option value="{{ $parent->id }}" 
                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mô tả -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
                    <textarea name="description" id="description" rows="3" 
                        class="mt-1 form-textarea block w-full rounded-md"
                        placeholder="Nhập mô tả danh mục">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trạng thái -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                        {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                        class="form-checkbox h-4 w-4 text-indigo-600 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Kích hoạt danh mục
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-white">
                    <i class="fas fa-times mr-2"></i>Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check mr-2"></i>Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9-]/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
    document.getElementById('slug').value = slug;
});
</script>
@endpush
@endsection 