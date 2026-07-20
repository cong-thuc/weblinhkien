@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 text-primary">Thêm Phiếu Xuất Kho</h2>
        <a href="{{ route('exports.index') }}" class="btn btn-secondary">
            ← Quay lại
        </a>
    </div>

    <!-- Nơi hiển thị thông báo lỗi (Nếu xuất quá số lượng) -->
    @if(session('error'))
        <div class="alert alert-danger fw-bold shadow-sm">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('exports.store') }}" method="POST">
                @csrf
                
                <h5 class="mb-3 text-dark fw-bold border-bottom pb-2">Thông tin chung</h5>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Ghi chú đợt xuất:</label>
                        <input type="text" name="note" class="form-control bg-light" placeholder="Ví dụ: Xuất hàng cho dự án sinh viên...">
                    </div>
                </div>

                <h5 class="mb-3 text-dark fw-bold border-bottom pb-2">Chi tiết linh kiện xuất</h5>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tên Linh Kiện <span class="text-danger">*</span></th>
                                <th>Vị trí lấy hàng (Kệ/Tủ) <span class="text-danger">*</span></th>
                                <th width="150">Số lượng <span class="text-danger">*</span></th>
                                <th width="200">Giá xuất (VNĐ)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <tr>
                                <!-- Chọn linh kiện -->
                                <td>
                                    <!-- Thêm id="component_select" và onchange="updateLocations()" -->
                                    <select name="component_id" id="component_select" class="form-select" required onchange="updateLocations()">
                                        <option value="">-- Chọn linh kiện --</option>
                                        @foreach($components as $comp)
                                            <option value="{{ $comp->id }}">{{ $comp->name }} (Tồn tổng: {{ $comp->quantity }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                
                                <!-- Chọn vị trí -->
                                <td>
                                    <!-- Thêm id="location_select" và bỏ vòng lặp PHP đi, để JS tự lo -->
                                    <select name="location_id" id="location_select" class="form-select" required>
                                        <option value="">-- Vui lòng chọn linh kiện trước --</option>
                                    </select>
                                </td>
                                
                                <!-- Nhập số lượng -->
                                <td>
                                    <input type="number" name="quantity" class="form-control" min="1" required placeholder="VD: 5">
                                </td>
                                
                                <!-- Nhập giá xuất -->
                                <td>
                                    <input type="number" name="price" class="form-control" placeholder="VD: 25000">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5 fw-bold py-2">
                        Lưu Phiếu Xuất
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Nhận mảng dữ liệu vị trí - linh kiện từ Controller (dưới dạng JSON)
    const stockData = {!! json_encode($stockDetails) !!};

    function updateLocations() {
        const componentId = document.getElementById('component_select').value;
        const locationSelect = document.getElementById('location_select');

        // Xóa hết các vị trí cũ đang hiển thị
        locationSelect.innerHTML = '<option value="">-- Chọn vị trí lấy hàng --</option>';

        if (componentId && stockData[componentId]) {
            const locations = stockData[componentId];
            
            if (locations.length === 0) {
                locationSelect.innerHTML = '<option value="">-- Linh kiện này đã hết ở mọi vị trí --</option>';
            } else {
                // In ra danh sách các kệ có chứa linh kiện này
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
</script>
@endsection