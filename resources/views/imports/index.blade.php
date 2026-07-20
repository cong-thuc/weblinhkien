@extends('layouts.app')

@section('content')
<h2>Quản lý Nhập Kho</h2>
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-dark fw-bold">Danh Sách Phiếu Nhập</h5>
        <a href="{{ route('imports.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-circle"></i> Thêm Phiếu Nhập Mới
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
                        <th>Mã Phiếu (ID)</th>
                        <th>Ngày Nhập</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($imports) && $imports->count() > 0)
                        @foreach($imports as $import)
                            <tr>
                                <td><b>#PN-{{ $import->id }}</b></td>
                                <td>{{ \Carbon\Carbon::parse($import->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('imports.show', $import->id) }}" class="btn btn-info btn-sm text-white">
                                        <i class="bi bi-eye"></i> Xem Chi Tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Chưa có phiếu nhập nào.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        
    </div>
</div>
@endsection