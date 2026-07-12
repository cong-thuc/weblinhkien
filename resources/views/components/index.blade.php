@extends('layouts.app')

@section('content')
<h2>Quản lý Linh Kiện</h2>
<div class="card shadow-sm mb-5"> <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-dark fw-bold">Danh Sách Linh Kiện</h5>
        <a href="{{ route('components.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Thêm Linh Kiện Mới
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
                        <th>STT</th>
                        <th>Hình ảnh</th>
                        <th>Mã (Code)</th>
                        <th>Tên linh kiện</th>
                        <th>Danh mục</th>
                        <th>Số lượng</th>
                        <th>Giá bán</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($components as $key => $component)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            @if($component->image)
                                <img src="{{ asset('storage/' . $component->image) }}" alt="Ảnh SP" width="50" height="50" class="rounded object-fit-cover" style="border: 1px solid #ddd;">
                            @else
                                <span class="badge bg-light text-dark border">Không có</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $component->code }}</span></td>
                        <td class="fw-bold text-primary">{{ $component->name }}</td>
                        
                        <td>{{ $component->category->name ?? 'N/A' }}</td>
                        
                        <td>
                            @if($component->quantity < 10)
                                <span class="text-danger fw-bold">{{ $component->quantity }}</span>
                            @else
                                <span class="text-success fw-bold">{{ $component->quantity }}</span>
                            @endif
                        </td>
                        <td>{{ number_format($component->price, 0, ',', '.') }} đ</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('components.edit', $component->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                
                                <form action="{{ route('components.destroy', $component->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa linh kiện này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Chưa có linh kiện nào trong kho.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $components->links() }}
        </div>
    </div>
</div>

@endsection