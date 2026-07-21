@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Thêm Linh Kiện Mới Vào Kho</h5>
    </div>
    <div class="card-body p-4">

       

        <form action="{{ route('components.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="form-label fw-bold">Hình ảnh linh kiện</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @error('image') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tên linh kiện</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="VD: Tụ điện hóa 10uF">
                    @error('name') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã linh kiện (Code)</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="VD: C-001">
                    @error('code') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- GOM DANH MỤC VÀ NGƯỜI THÊM VÀO CÙNG 1 HÀNG ĐỂ CÂN ĐỐI GIAO DIỆN -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Thuộc danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
                </div>
                
                <!-- 🌟 BỔ SUNG CỘT NGƯỜI THÊM LINH KIỆN (BẢN XỊN CÓ ICON) 🌟 -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Người thêm linh kiện</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary"><i class="fas fa-user-edit"></i></span>
                        <input type="text" name="creator_name" class="form-control" value="{{ old('creator_name') }}" placeholder="VD: Nguyễn Văn A">
                    </div>
                    @error('creator_name') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Mô tả chi tiết</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Nhập thông số kỹ thuật hoặc ghi chú thêm...">{{ old('description') }}</textarea>
                @error('description') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
            </div>

            <div class="d-flex gap-2 justify-content-end border-top pt-3">
                <a href="{{ route('components.index') }}" class="btn btn-light border px-4 fw-bold">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary px-5 fw-bold"><i class="fas fa-save me-2"></i> Lưu linh kiện</button>
            </div>
        </form>
    </div>
</div>
@endsection