@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="container-fluid px-4">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa sản phẩm</h1>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Thông tin cơ bản -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-medium">Thông tin cơ bản</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="name">Tên sản phẩm</label>
                            <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="category_id">Danh mục</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="form-label" for="price">Giá bán</label>
                                <div class="relative">
                                    <input type="number" name="price" id="price" class="form-input pr-12" value="{{ old('price', $product->price) }}" required>
                                    <span class="absolute right-3 top-2 text-gray-500">VNĐ</span>
                                </div>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="stock">Số lượng</label>
                                <input type="number" name="stock" id="stock" class="form-input" value="{{ old('stock', $product->stock) }}" required>
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mô tả và thông số kỹ thuật -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-lg font-medium">Mô tả & Thông số kỹ thuật</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label" for="description">Mô tả sản phẩm</label>
                            <textarea name="description" id="description" rows="4" class="form-input">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Thông số kỹ thuật</label>
                            <div id="specifications">
                                @foreach($product->specifications ?? [] as $key => $value)
                                    <div class="grid grid-cols-2 gap-4 mb-2">
                                        <input type="text" name="spec_names[]" class="form-input" value="{{ $key }}" placeholder="Tên thông số">
                                        <div class="flex">
                                            <input type="text" name="spec_values[]" class="form-input flex-1" value="{{ $value }}" placeholder="Giá trị">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                         
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hình ảnh -->
            <div class="card mt-6">
                <div class="card-header">
                    <h2 class="text-lg font-medium">Hình ảnh sản phẩm</h2>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($product->images as $index => $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image) }}" alt="Product image" class="w-full h-40 object-cover rounded-lg">
                                <button type="button" 
                                    class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                    onclick="deleteImage('{{ $image }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <label class="form-label" for="images">Thêm hình ảnh mới</label>
                        <input type="file" name="images[]" id="images" class="form-input" multiple accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">Có thể chọn nhiều hình ảnh. Định dạng: JPG, PNG. Tối đa 2MB/ảnh</p>
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Trạng thái -->
            <div class="card mt-6">
                <div class="card-body flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" class="form-checkbox" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="ml-2 text-sm font-medium text-gray-700" for="is_active">
                            Kích hoạt sản phẩm
                        </label>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function addSpec() {
        const container = document.getElementById('specifications');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-2 gap-4 mb-2';
        div.innerHTML = `
            <input type="text" name="spec_names[]" class="form-input" placeholder="Tên thông số">
            <div class="flex">
                <input type="text" name="spec_values[]" class="form-input flex-1" placeholder="Giá trị">
                <button type="button" class="ml-2 text-red-600 hover:text-red-800" onclick="removeSpec(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
    }

    function removeSpec(button) {
        button.closest('.grid').remove();
    }

    function deleteImage(imagePath) {
        if (confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')) {
            // Tạo form ẩn để submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.products.deleteImage', $product->id) }}';
            
            // Thêm CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Thêm method PUT
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            // Thêm đường dẫn hình ảnh
            const imageInput = document.createElement('input');
            imageInput.type = 'hidden';
            imageInput.name = 'image';
            imageInput.value = imagePath;
            form.appendChild(imageInput);

            // Submit form
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection 