@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Thêm Vị Trí Mới</h2>
        <a href="{{ route('locations.index') }}" class="btn btn-secondary">
            Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('locations.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Vị Trí (Ví dụ: Kệ A1, Tủ Kính B2) <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="Nhập tên kệ/tủ...">
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Sức Chứa Tối Đa (Số lượng) <span class="text-danger">*</span></label>
                    <input type="number" name="max_capacity" class="form-control" min="1" required placeholder="Ví dụ: 500">
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        Lưu Vị Trí
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection