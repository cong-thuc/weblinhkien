@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 text-primary">Quản lý Vị trí kho</h2>
        <a href="{{ route('locations.create') }}" class="btn btn-primary fw-bold">
            + Thêm vị trí mới
        </a>
    </div>

    <!-- Hiển thị thông báo thành công hoặc lỗi -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="60">STT</th>
                        <th>Tên Vị Trí (Kệ/Tủ)</th>
                        <th>Linh kiện đang chứa</th> <!-- Cột mới -->
                        <th>Đã dùng / Tối đa</th>   <!-- Cột mới -->
                        <th>Còn trống</th>          <!-- Cột mới -->
                        <th width="120">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $key => $loc)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td class="text-start fw-bold text-primary">{{ $loc->name }}</td>
                        
                        <!-- Cột hiển thị danh sách linh kiện -->
                        <td class="text-start">
                            @if(count($loc->groupedComponents) > 0)
                                <ul class="mb-0" style="padding-left: 20px;">
                                    @foreach($loc->groupedComponents as $name => $qty)
                                        <li>{{ $name }}: <span class="badge bg-secondary">{{ number_format($qty, 0, ',', '.') }}</span></li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted fst-italic">Đang trống</span>
                            @endif
                        </td>
                        
                        <!-- Cột hiển thị tỷ lệ lấp đầy -->
                        <td>
                            <span class="text-dark fw-bold">{{ number_format($loc->used, 0, ',', '.') }}</span> / 
                            <span class="text-muted">{{ number_format($loc->max_capacity, 0, ',', '.') }}</span>
                        </td>
                        
                        <!-- Cột hiển thị chỗ trống còn lại -->
                        <td>
                            @if($loc->remaining > 0)
                                <span class="badge bg-success" style="font-size: 13px;">+{{ number_format($loc->remaining, 0, ',', '.') }}</span>
                            @else
                                <span class="badge bg-danger" style="font-size: 13px;">Đã đầy</span>
                            @endif
                        </td>
                        
                        <td>
                            <!-- Nút Sửa -->
                            <a href="{{ route('locations.edit', $loc->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <!-- Nút Xóa -->
                            <form action="{{ route('locations.destroy', $loc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vị trí này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Phân trang -->
    <div class="mt-3">
        {{ $locations->links() }}
    </div>
</div>
@endsection