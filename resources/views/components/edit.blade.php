@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <!-- Tiêu đề màu Vàng (Warning) đặc trưng cho hành động Sửa -->
    <div class="card-header bg-warning text-dark py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Chỉnh Sửa Linh Kiện: {{ $component->name }}</h5>
    </div>
    
    <div class="card-body p-4">

        <!-- 🌟 MÁY QUÉT LỖI: HIỂN THỊ TẤT CẢ LỖI BỊ ẨN 🌟 -->
        @if($errors->any())
            <div class="alert alert-danger shadow-sm mb-4">
                <ul class="mb-0 fw-bold">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- ==================================================== -->

        <form action="{{ route('components.update', $component->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Bắt buộc phải có @method('PUT') đối với form Sửa -->
            
            <!-- Phần hiển thị và cập nhật ảnh -->
            <div class="mb-4 p-3 bg-light rounded border">
                <label class="form-label fw-bold">Hình ảnh linh kiện hiện tại</label>
                <div class="mb-3">
                    @if($component->image)
                        <img src="{{ asset('storage/' . $component->image) }}" class="rounded shadow-sm border" width="120" height="120" style="object-fit: cover;" alt="{{ $component->name }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-white rounded shadow-sm text-muted border" style="width: 120px; height: 120px;">
                            <i class="fas fa-image fa-2x"></i>
                        </div>
                    @endif
                </div>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted fst-italic">Bỏ trống nếu bạn không muốn thay đổi ảnh.</small>
                @error('image') <br><small class="text-danger fw-bold">{{ $message }}</small> @enderror
            </div>

            <!-- Tên và Mã Code -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tên linh kiện</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $component->name) }}" required>
                    @error('name') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã linh kiện (Code)</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $component->code) }}" required>
                    @error('code') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Danh mục và Người thêm -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Thuộc danh mục</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $component->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Người thêm linh kiện</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-secondary"><i class="fas fa-user-edit"></i></span>
                        <input type="text" name="creator_name" class="form-control" value="{{ old('creator_name', $component->creator_name) }}" required>
                    </div>
                    @error('creator_name') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Mô tả chi tiết -->
            <div class="mb-4">
                <label class="form-label fw-bold">Mô tả chi tiết</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $component->description) }}</textarea>
                @error('description') <small class="text-danger fw-bold">{{ $message }}</small> @enderror
            </div>

            <!-- Nút hành động -->
            <div class="d-flex gap-2 justify-content-end border-top pt-3">
                <a href="{{ route('components.index') }}" class="btn btn-light border px-4 fw-bold">Hủy bỏ</a>
                <button type="submit" class="btn btn-warning px-5 fw-bold text-dark"><i class="fas fa-save me-2"></i> Lưu cập nhật</button>
            </div>
        </form>
    </div>
</div>
@endsection