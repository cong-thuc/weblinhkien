@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 text-primary"><i class="fas fa-file-export me-2"></i>Thêm Phiếu Xuất Kho</h2>
        <a href="{{ route('exports.index') }}" class="btn btn-secondary shadow-sm">
            ← Quay lại
        </a>
    </div>

    <!-- Nơi hiển thị thông báo lỗi hệ thống -->
    @if(session('error'))
        <div class="alert alert-danger fw-bold shadow-sm">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Máy quét lỗi Validation -->
    @if($errors->any())
        <div class="alert alert-danger fw-bold shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('exports.store') }}" method="POST">
                @csrf
                
                <h5 class="mb-3 text-dark fw-bold border-bottom pb-2">Thông tin chung</h5>
                <div class="row mb-4">
                    <!-- Bổ sung cột Người thực hiện -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Người thực hiện xuất kho <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-user-check"></i></span>
                            <input type="text" name="exporter_name" class="form-control" value="{{ old('exporter_name') }}" placeholder="VD: Nguyễn Văn A" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Ghi chú đợt xuất:</label>
                        <input type="text" name="note" class="form-control bg-light" value="{{ old('note') }}" placeholder="Ví dụ: Xuất hàng cho dự án sinh viên...">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-end mb-3 border-bottom pb-2">
                    <h5 class="mb-0 text-dark fw-bold">Chi tiết linh kiện xuất</h5>
                    <button type="button" class="btn btn-sm btn-success fw-bold shadow-sm" id="add-row-btn">
                        <i class="fas fa-plus me-1"></i> Thêm linh kiện
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle" id="export-table">
                        <thead class="table-light">
                            <tr>
                                <th>Tên Linh Kiện <span class="text-danger">*</span></th>
                                <th>Vị trí lấy hàng (Kệ/Tủ) <span class="text-danger">*</span></th>
                                <th width="150">Số lượng <span class="text-danger">*</span></th>
                                <th width="200">Giá xuất (VNĐ)</th>
                                <th width="80">Xóa</th>
                            </tr>
                        </thead>
                        <tbody id="export-tbody">
                            <tr>
                                <!-- Đổi name thành mảng [] và dùng class thay vì id -->
                                <td>
                                    <select name="component_id[]" class="form-select component-select" required>
                                        <option value="">-- Chọn linh kiện --</option>
                                        @foreach($components as $comp)
                                            <option value="{{ $comp->id }}">{{ $comp->name }} (Tồn tổng: {{ $comp->quantity }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <td>
                                    <select name="location_id[]" class="form-select location-select" required>
                                        <option value="">-- Vui lòng chọn linh kiện trước --</option>
                                    </select>
                                </td>
                                
                                <td>
                                    <input type="number" name="quantity[]" class="form-control" min="1" required placeholder="VD: 5">
                                </td>
                                
                                <td>
                                    <input type="number" name="price[]" class="form-control" min="0" placeholder="VD: 25000">
                                </td>

                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row-btn shadow-sm" disabled title="Xóa dòng này">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-end mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary px-5 fw-bold py-2 shadow-sm">
                        <i class="fas fa-save me-2"></i> Lưu Phiếu Xuất
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Nhận mảng dữ liệu vị trí - linh kiện từ Controller (dưới dạng JSON)
    const stockData = {!! json_encode($stockDetails) !!};

    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('export-tbody');
        const addRowBtn = document.getElementById('add-row-btn');

        // 1. Lắng nghe sự kiện thay đổi Linh kiện (Dùng Event Delegation cho các dòng tự sinh)
        tbody.addEventListener('change', function (e) {
            // Nếu phần tử vừa thay đổi có class là 'component-select'
            if (e.target.classList.contains('component-select')) {
                const tr = e.target.closest('tr'); // Tìm dòng chứa nó
                const locationSelect = tr.querySelector('.location-select'); // Tìm ô Vị trí CỦA DÒNG ĐÓ
                const componentId = e.target.value;

                // Xóa các vị trí cũ
                locationSelect.innerHTML = '<option value="">-- Chọn vị trí lấy hàng --</option>';

                if (componentId && stockData[componentId]) {
                    const locations = stockData[componentId];
                    
                    if (locations.length === 0) {
                        locationSelect.innerHTML = '<option value="">-- Linh kiện này đã hết ở mọi vị trí --</option>';
                    } else {
                        // In ra danh sách các kệ
                        locations.forEach(loc => {
                            const option = document.createElement('option');
                            option.value = loc.id;
                            option.text = `${loc.name} (Có sẵn: ${loc.stock} cái)`;
                            locationSelect.appendChild(option);
                        });
                    }
                } else {
                    locationSelect.innerHTML = '<option value="">-- Vui lòng chọn linh kiện trước --</option>';
                }
            }
        });

        // 2. Thêm dòng mới
        addRowBtn.addEventListener('click', function () {
            const firstRow = tbody.querySelector('tr');
            const newRow = firstRow.cloneNode(true); // Nhân bản html của dòng đầu

            // Reset dữ liệu dòng mới
            newRow.querySelector('.component-select').selectedIndex = 0;
            newRow.querySelector('.location-select').innerHTML = '<option value="">-- Vui lòng chọn linh kiện trước --</option>';
            
            const inputs = newRow.querySelectorAll('input');
            inputs.forEach(input => input.value = '');

            // Bật nút xóa cho dòng mới
            const removeBtn = newRow.querySelector('.remove-row-btn');
            removeBtn.disabled = false;

            tbody.appendChild(newRow);
        });

        // 3. Xóa dòng
        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-row-btn');
            if (removeBtn) {
                // Không cho phép xóa nếu chỉ còn 1 dòng
                if (tbody.querySelectorAll('tr').length > 1) {
                    removeBtn.closest('tr').remove();
                }
            }
        });
    });
</script>
@endsection