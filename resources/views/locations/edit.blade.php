@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Cập Nhật Vị Trí: <span class="text-primary">{{ $location->name }}</span></h2>
        <a href="{{ route('locations.index') }}" class="btn btn-secondary">
            Quay lại
        </a>
    </div>

    <!-- Đoạn mã được thêm vào để hiển thị thông báo lỗi chặn hạ sức chứa -->
    @if(session('error'))
        <div class="alert alert-danger fw-bold shadow-sm">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('locations.update', $location->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Vị Trí <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $location->name }}" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Sức Chứa Tối Đa <span class="text-danger">*</span></label>
                    <input type="number" name="max_capacity" class="form-control" value="{{ $location->max_capacity }}" min="1" required>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-warning px-4 fw-bold">
                        Cập Nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection