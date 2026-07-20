@extends('layouts.app') 

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0">Chi Tiết Phiếu Nhập #PN-{{ $import->id }}</h2>
        <a href="{{ route('imports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-bold">
            Thông tin chung
        </div>
        <div class="card-body">
            <p><strong>Ngày nhập:</strong> {{ \Carbon\Carbon::parse($import->created_at)->format('d/m/Y H:i') }}</p>
            <p><strong>Người nhập:</strong> <span class="badge bg-secondary">{{ $import->importer_name ?? $import->user->name ?? 'Quản trị viên' }}</span></p>
            <p><strong>Ghi chú:</strong> {{ $import->note ?? 'Không có' }}</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-bold">
            Danh sách linh kiện đã nhập
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0 text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>STT</th>
                        <th>Tên Linh Kiện</th>
                        <th>Vị trí cất</th> <!-- CỘT 3 -->
                        <th>Số Lượng Nhập</th> <!-- CỘT 4 -->
                        <th>Giá Nhập (VNĐ)</th> <!-- CỘT 5 -->
                        <th>Thành Tiền</th> <!-- CỘT 6 -->
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($import->details as $key => $detail)
                        @php 
                            $thanhTien = $detail->quantity * ($detail->price ?? 0); 
                            $total += $thanhTien;
                        @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="text-start">{{ $detail->component->name ?? 'Linh kiện không xác định' }}</td>
                            
                            <!-- ĐÃ THÊM Ô DỮ LIỆU VỊ TRÍ CẤT VÀO ĐÂY -->
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $detail->location->name ?? 'Chưa xếp' }}
                                </span>
                            </td>
                            
                            <td><span class="badge bg-success" style="font-size: 14px;">+{{ $detail->quantity }}</span></td>
                            <td>{{ number_format($detail->price ?? 0, 0, ',', '.') }} đ</td>
                            <td>{{ number_format($thanhTien, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                    
                    <tr>
                        <!-- SỬA COLSPAN THÀNH 5 ĐỂ DỒN ĐÚNG VỊ TRÍ CỘT THÀNH TIỀN -->
                        <td colspan="5" class="text-end fw-bold">Tổng cộng:</td>
                        <td class="fw-bold text-danger">{{ number_format($total, 0, ',', '.') }} đ</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection