@extends('layouts.app') 

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 text-danger"><i class="bi bi-box-arrow-up-right"></i> Chi Tiết Phiếu Xuất #PX-{{ $export->id }}</h2>
        <a href="{{ route('exports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white fw-bold text-dark">
            <i class="bi bi-info-circle text-primary"></i> Thông tin chung
        </div>
        <div class="card-body">
            <p><strong>Ngày xuất hàng:</strong> {{ \Carbon\Carbon::parse($export->created_at)->format('d/m/Y H:i') }}</p>
            <p class="mb-0"><strong>Ghi chú / Lý do:</strong> {{ $export->note ?? 'Không có ghi chú' }}</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold text-dark">
            <i class="bi bi-list-check text-success"></i> Danh sách linh kiện đã xuất
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0 text-center align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>STT</th>
                        <th>Tên Linh Kiện</th>
                        <th>Số Lượng Xuất</th>
                        <th>Đơn Giá Xuất</th>
                        <th>Thành Tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($export->details as $key => $detail)
                        @php 
                            $thanhTien = $detail->quantity * ($detail->price ?? 0); 
                            $total += $thanhTien;
                        @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="text-start fw-bold text-primary">{{ $detail->component->name ?? 'Linh kiện đã bị xóa' }}</td>
                            <td><span class="badge bg-danger fs-6">- {{ $detail->quantity }}</span></td>
                            <td>{{ number_format($detail->price ?? 0, 0, ',', '.') }} đ</td>
                            <td class="text-danger fw-bold">{{ number_format($thanhTien, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                    <tr class="bg-light">
                        <td colspan="4" class="text-end fw-bold fs-5">Tổng cộng:</td>
                        <td class="fw-bold text-danger fs-5">{{ number_format($total, 0, ',', '.') }} đ</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection