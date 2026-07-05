@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">Chỉnh Sửa Danh Mục</div>
                <div class="card-body">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Tên danh mục</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Hủy quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection