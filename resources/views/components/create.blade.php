@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0 fw-bold">Thêm Linh Kiện Mới Vào Kho</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('components.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold">Hình ảnh linh kiện</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tên linh kiện</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Mã linh kiện (Code)</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                    @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Thuộc danh mục</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Đơn giá (đ)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', 0) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Mô tả chi tiết</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('components.index') }}" class="btn btn-secondary px-4">Hủy</a>
                <button type="submit" class="btn btn-primary px-4">Lưu linh kiện</button>
            </div>
        </form>
    </div>
</div>
@endsection