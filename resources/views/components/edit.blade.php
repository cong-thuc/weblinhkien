@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark py-3">
        <h5 class="mb-0 fw-bold">Chỉnh Sửa Linh Kiện: {{ $component->name }}</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('components.update', $component->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <div class="mb-3">
                <label class="form-label fw-bold">Hình ảnh linh kiện hiện tại</label>
                <div class="mb-2">
                    @if($component->image)
                        <img src="{{ asset('storage/' . $component->image) }}" alt="Ảnh SP" width="100" class="rounded border">
                    @else
                        <span class="text-muted">Chưa có hình ảnh</span>
                    @endif
                </div>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">Bỏ trống nếu không muốn thay đổi ảnh.</small>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tên linh kiện</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $component->name) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Mã linh kiện (Code)</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $component->code) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Thuộc danh mục</label>
                <select name="category_id" class="form-select">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $component->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $component->quantity) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Đơn giá (đ)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', (int)$component->price) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Mô tả chi tiết</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $component->description) }}</textarea>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('components.index') }}" class="btn btn-secondary px-4">Hủy quay lại</a>
                <button type="submit" class="btn btn-warning px-4">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection