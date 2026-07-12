@extends('layouts.app')

@section('title','Dashboard')

@section('content')

<h2>Dashboard</h2>

<div class="row">

    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Danh mục</h5>
                <h2>{{ $categoryCount ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <!-- <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Nhà cung cấp</h5>
                <h2>{{ $supplierCount ?? 0 }}</h2>
            </div>
        </div>
    </div> -->

    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Linh kiện</h5>
                <h2>{{ $componentCount ?? 0 }}</h2>
            </div>
        </div>
    </div>


    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white h-100 shadow-sm border-0">
            <div class="card-body">
                <h5>Nhập kho</h5>
                <h2>{{ $importCount ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5>Xuất kho</h5>
                <h2>{{ $exportCount ?? 0 }}</h2>
            </div>
        </div>
    </div>

</div>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3">
                <i class="bi bi-bar-chart-line-fill text-primary"></i> Biểu đồ Thống kê Tổng quan
            </div>
            <div class="card-body">
                <canvas id="myChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('myChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar', // Chọn kiểu biểu đồ cột
            data: {
                // Tên của 4 cột
                labels: ['Danh mục', 'Linh kiện', 'Nhập kho', 'Xuất kho'],
                datasets: [{
                    label: 'Số lượng',
                    // Lấy chính xác 4 biến từ Laravel truyền vào đây
                    data: [
                        {{ $categoryCount ?? 0 }},
                        {{ $componentCount ?? 0 }},
                        {{ $importCount ?? 0 }},
                        {{ $exportCount ?? 0 }}
                    ],
                    // Phối màu cho 4 cột y hệt màu của 4 thẻ card ở trên
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.8)', // Xanh dương (Danh mục)
                        'rgba(255, 193, 7, 0.8)',  // Vàng (Linh kiện)
                        'rgba(13, 202, 240, 0.8)', // Xanh lơ (Nhập kho)
                        'rgba(220, 53, 69, 0.8)'   // Đỏ (Xuất kho)
                    ],
                    borderWidth: 0,
                    borderRadius: 5 // Bo góc cho cột nhìn hiện đại hơn
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Ẩn phần chú thích thừa
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Buộc trục Y nhảy từng số nguyên (1, 2, 3...)
                        }
                    }
                }
            }
        });
    });
</script>

@endsection

