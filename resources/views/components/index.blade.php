@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Tiêu đề và Nút thêm mới -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="m-0 text-primary fw-bold">
            Quản lý Linh kiện
        </h3>
        <a href="{{ route('components.create') }}" class="btn btn-primary fw-bold shadow-sm">
            + Thêm Linh Kiện Mới
        </a>
    </div>

    <!-- Thông báo thành công / lỗi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>Tuyệt vời!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>Lỗi!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Bảng dữ liệu -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle text-center mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="py-3 text-muted">STT</th>
                            <th class="py-3 text-muted">Hình ảnh</th>
                            <th class="py-3 text-muted">Mã (Code)</th>
                            <th class="py-3 text-muted text-start">Tên linh kiện</th>
                            <th class="py-3 text-muted">Danh mục</th>
                            <!-- ĐÃ XÓA CỘT TỒN KHO Ở ĐÂY -->
                            <th class="py-3 text-muted">Người thêm</th>
                            <th class="py-3 text-muted">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($components as $key => $comp)
                            <tr class="border-bottom">
                                <!-- STT -->
                                <td class="fw-bold text-secondary">{{ $key + 1 }}</td>
                                
                                <!-- Hình ảnh -->
                                <td>
                                    @if($comp->image)
                                        <img src="{{ asset('storage/' . $comp->image) }}" class="rounded shadow-sm" width="50" height="50" style="object-fit: cover; border: 1px solid #ddd;" alt="{{ $comp->name }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light rounded shadow-sm text-muted" style="width: 50px; height: 50px; margin: 0 auto; border: 1px dashed #ccc;">
                                            <span>Ảnh</span>
                                        </div>
                                    @endif
                                </td>
                                
                                <!-- Mã Code -->
                                <td>
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ $comp->code }}</span>
                                </td>
                                
                                <!-- Tên linh kiện -->
                                <td class="text-start fw-bold text-primary">
                                    {{ $comp->name }}
                                </td>
                                
                                <!-- Danh mục -->
                                <td>
                                    <span class="badge bg-info text-dark px-2 py-1">{{ $comp->category->name ?? 'Không có' }}</span>
                                </td>

                                <!-- Người thêm -->
                                <td>
                                    <span class="text-muted fw-semibold">
                                        {{ $comp->creator_name ?? 'N/A' }}
                                    </span>
                                </td>
                                
                                <!-- Hành động: Dùng nút có màu đặc và chữ rõ ràng -->
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Nút Sửa màu Vàng (Warning) -->
                                        <a href="{{ route('components.edit', $comp->id) }}" class="btn btn-sm btn-warning text-dark fw-bold shadow-sm px-3" style="min-width: 70px;">
                                            Sửa
                                        </a>
                                        
                                        <!-- Nút Xóa màu Đỏ (Danger) -->
                                        <form action="{{ route('components.destroy', $comp->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa linh kiện này không? (Lưu ý: Không thể xóa nếu đã có lịch sử nhập/xuất)');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold shadow-sm px-3" style="min-width: 70px;">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <h5 class="fw-bold">Kho hiện chưa có linh kiện nào</h5>
                                        <p>Vui lòng thêm linh kiện mới để bắt đầu quản lý.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Phân trang -->
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-center">
                {{ $components->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection