@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 text-primary"><i class="bi bi-box-arrow-in-down"></i> Thêm Phiếu Nhập Kho</h2>
        <a href="{{ route('imports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('imports.store') }}" method="POST">
        @csrf
        <!-- 1. THÔNG TIN CHUNG -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold text-dark">
                <i class="bi bi-info-circle text-primary"></i> Thông tin chung
            </div>
            <div class="card-body row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Người thực hiện nhập (*):</label>
                    <input type="text" name="importer_name" class="form-control" required placeholder="Gõ tên người nhập vào đây (VD: Nguyễn Văn A)">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Ghi chú đợt nhập:</label>
                    <input type="text" name="note" class="form-control" placeholder="Ví dụ: Nhập hàng đợt 1 tháng 7...">
                </div>
            </div>
        </div>

        <!-- 2. CHI TIẾT LINH KIỆN NHẬP (BẢNG ĐỘNG) -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold text-dark d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-check text-success"></i> Danh sách linh kiện nhập vào kho</span>
                <button type="button" class="btn btn-sm btn-success fw-bold" id="btn-add-row">
                    + Thêm linh kiện
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0 text-center align-middle" id="table-details">
                    <thead class="table-light">
                        <tr>
                            <th>Tên Linh Kiện</th>
                            <th>Vị trí cất (Kệ/Tủ)</th>
                            <th width="150">Số lượng</th>
                            <th width="200">Giá nhập (VNĐ)</th>
                            <th width="80">Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-details">
                        <!-- Dòng nhập liệu đầu tiên (Mặc định) -->
                        <tr>
                            <td>
                                <select name="details[0][component_id]" class="form-select" required>
                                    <option value="">-- Chọn linh kiện --</option>
                                    @foreach($components as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="details[0][location_id]" class="form-select" required>
                                    <option value="">-- Chọn vị trí --</option>
                                    @foreach($locations as $loc)
                                        <!-- Khóa (disabled) không cho chọn nếu sức chứa còn lại <= 0 -->
                                        <option value="{{ $loc->id }}" {{ $loc->remaining <= 0 ? 'disabled' : '' }}>
                                        {{ $loc->name }} (Còn trống: {{ $loc->remaining }})
                                    </option>
                            @endforeach
                        </select>
                            </td>
                            <td>
                                <input type="number" name="details[0][quantity]" class="form-control" min="1" required placeholder="VD: 50">
                            </td>
                            <td>
                                <input type="number" name="details[0][price]" class="form-control" min="0" required placeholder="VD: 15000">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                <i class="bi bi-save"></i> Lưu Phiếu Nhập
            </button>
        </div>
    </form>
</div>

<!-- SCRIPTS ĐỂ XỬ LÝ NÚT THÊM/XÓA DÒNG BẰNG JAVASCRIPT -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let rowIndex = 1; // Biến đếm index cho mảng details

        // Lưu sẵn danh sách option để xài lại khi tạo dòng mới
        const componentOptions = `@foreach($components as $item)<option value="{{ $item->id }}">{{ $item->name }}</option>@endforeach`;
        // Cập nhật lại chuỗi tạo option trong JavaScript
        const locationOptions = `@foreach($locations as $loc)<option value="{{ $loc->id }}" {{ $loc->remaining <= 0 ? 'disabled' : '' }}>{{ $loc->name }} (Còn trống: {{ $loc->remaining }} / Tối đa: {{ $loc->max_capacity }})</option>@endforeach`;

        // Sự kiện khi bấm "Thêm linh kiện"
        document.getElementById('btn-add-row').addEventListener('click', function() {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select name="details[${rowIndex}][component_id]" class="form-select" required>
                        <option value="">-- Chọn linh kiện --</option>
                        ${componentOptions}
                    </select>
                </td>
                <td>
                    <select name="details[${rowIndex}][location_id]" class="form-select" required>
                        <option value="">-- Chọn vị trí --</option>
                        ${locationOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="details[${rowIndex}][quantity]" class="form-control" min="1" required placeholder="VD: 50">
                </td>
                <td>
                    <input type="number" name="details[${rowIndex}][price]" class="form-control" min="0" required placeholder="VD: 15000">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="bi bi-trash"></i></button>
                </td>
            `;
            document.getElementById('tbody-details').appendChild(tr);
            rowIndex++;
        });

        // Sự kiện khi bấm nút Xóa (Thùng rác)
        document.getElementById('table-details').addEventListener('click', function(e) {
            if(e.target.closest('.btn-remove-row')) {
                const rowCount = document.querySelectorAll('#tbody-details tr').length;
                if (rowCount > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Phiếu nhập phải có ít nhất 1 dòng linh kiện!');
                }
            }
        });
    });
</script>
@endsection