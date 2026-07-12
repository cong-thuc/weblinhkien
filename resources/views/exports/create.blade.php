@extends('layouts.app') 

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-box-arrow-up-right text-danger"></i> Thêm Phiếu Xuất Kho</h2>
        <a href="{{ route('exports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('exports.store') }}" method="POST">
                @csrf 

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-danger">Linh kiện cần xuất (*):</label>
                        <select class="form-select" name="component_id" required>
                            <option value="">-- Chọn linh kiện --</option>
                            @foreach($components as $component)
                                <option value="{{ $component->id }}">
                                    {{ $component->name }} (Tồn kho: {{ $component->quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-danger">Số lượng xuất đi (*):</label>
                        <input type="number" class="form-control" name="quantity" min="1" placeholder="VD: 50" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Giá xuất (VNĐ) - Tùy chọn:</label>
                        <input type="number" class="form-control" name="price" min="0" placeholder="VD: 25000">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Ghi chú (Lý do xuất/Người nhận):</label>
                    <textarea class="form-control" name="note" rows="3" placeholder="Ghi chú đợt xuất hàng..."></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-check2-circle"></i> Lưu Phiếu Xuất
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection