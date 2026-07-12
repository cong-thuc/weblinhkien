@extends('layouts.app') 

@section('content')
<h2 class="mb-4">
    <i class="bi bi-box-arrow-up-right text-danger"></i> Quản lý Xuất Kho
</h2>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-dark fw-bold">Danh Sách Phiếu Xuất</h5>
        
        <a href="{{ route('exports.create') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-plus-circle"></i> Thêm Phiếu Xuất Mới
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Mã Phiếu Xuất</th>
                        <th>Ngày Xuất</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($exports) && $exports->count() > 0)
                        @foreach($exports as $export)
                            <tr>
                                <td><b>#PX-{{ $export->id }}</b></td>
                                <td>{{ \Carbon\Carbon::parse($export->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('exports.show', $export->id) }}" class="btn btn-info btn-sm text-white">
                                        <i class="bi bi-eye"></i> Xem Chi Tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Chưa có phiếu xuất nào.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            @if(isset($exports) && method_exists($exports, 'links'))
                {{ $exports->links() }}
            @endif
        </div>
    </div>
</div>
@endsection