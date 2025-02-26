@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="card-title fw-semibold mb-0">Thêm sản phẩm mới</h5>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                            id="category_id" name="category_id" required>
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="price" class="form-label">Giá bán</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                id="price" name="price" value="{{ old('price') }}" required min="0" step="1000">
                            <span class="input-group-text">đ</span>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('sale_price') is-invalid @enderror" 
                                id="sale_price" name="sale_price" value="{{ old('sale_price') }}" min="0" step="1000">
                            <span class="input-group-text">đ</span>
                        </div>
                        @error('sale_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="quantity" class="form-label">Số lượng</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                            id="quantity" name="quantity" value="{{ old('quantity', 0) }}" required min="0">
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="brand" class="form-label">Thương hiệu</label>
                        <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                            id="brand" name="brand" value="{{ old('brand') }}" required>
                        @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                        id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Thông số kỹ thuật</label>
                    <div class="row" id="specifications">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="specifications[cpu]" 
                                placeholder="CPU" value="{{ old('specifications.cpu') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="specifications[ram]" 
                                placeholder="RAM" value="{{ old('specifications.ram') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="specifications[storage]" 
                                placeholder="Ổ cứng" value="{{ old('specifications.storage') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="specifications[screen]" 
                                placeholder="Màn hình" value="{{ old('specifications.screen') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="specifications[gpu]" 
                                placeholder="Card đồ họa" value="{{ old('specifications.gpu') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="specifications[battery]" 
                                placeholder="Pin" value="{{ old('specifications.battery') }}">
                        </div>
                    </div>
                    @error('specifications')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Hình ảnh sản phẩm</label>
                    <input type="file" class="form-control @error('images') is-invalid @enderror" 
                        name="images[]" multiple accept="image/*">
                    <div class="form-text">Có thể chọn nhiều hình ảnh</div>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" 
                            name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Kích hoạt</label>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Thêm sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.invalid-feedback {
    font-size: 0.875rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}
</style>
@endpush 