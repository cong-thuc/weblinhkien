@extends('layouts.app') 

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Thêm Phiếu Nhập Kho</h2>
       <a href="{{ route('imports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('imports.store') }}" method="POST">
                @csrf <div class="row">
                    <!-- <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nhà cung cấp:</label>
                        <select class="form-select" name="supplier_id">
                            <option value="">-- Chọn nhà cung cấp (Tùy chọn) --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div> -->

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-primary">Linh kiện cần nhập (*):</label>
                        <select class="form-select" name="component_id" required>
                            <option value="">-- Chọn linh kiện --</option>
                            @foreach($components as $component)
                                <option value="{{ $component->id }}">
                                    {{ $component->name }} (Tồn kho hiện tại: {{ $component->quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-primary">Số lượng nhập (*):</label>
                        <input type="number" class="form-control" name="quantity" min="1" placeholder="VD: 100" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Giá nhập (VNĐ):</label>
                        <input type="number" class="form-control" name="price" min="0" placeholder="VD: 15000">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Ghi chú thêm:</label>
                    <textarea class="form-control" name="note" rows="3" placeholder="Ghi chú đợt nhập hàng..."></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> Lưu Phiếu Nhập
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection