<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Quản trị hệ thống') - Quản lý Linh kiện</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            overflow-x: hidden;
            background-color: #f8f9fa; /* Màu nền xám nhạt cho nội dung dễ nhìn hơn */
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #212529; /* Đổi sang màu đen nhám sang trọng hơn */
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .sidebar .brand {
            font-size: 1.2rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #495057;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            margin: 4px 10px;
            border-radius: 8px;
            transition: 0.2s;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #0d6efd; /* Màu xanh Primary của Bootstrap */
            color: white;
        }

        .main-content {
            flex-grow: 1;
            min-width: 0; /* Tránh lỗi tràn layout ngang */
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="d-flex">
    <div class="sidebar d-flex flex-column">
        <div class="brand text-white text-center py-4 mb-2">
            <i class="bi bi-cpu"></i> Admin Panel
        </div>

        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Quản lý danh mục
        </a>

        <a href="{{ route('components.index') }}" class="{{ request()->routeIs('components.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Quản lý linh kiện   
        </a>


        <li class="nav-item">
            <a href="{{ route('locations.index') }}" class="nav-link text-white {{ request()->routeIs('locations.*') ? 'active bg-primary rounded' : '' }}">
                <i class="bi bi-geo-alt"></i> Quản lý vị trí kho
            </a>
        </li>

    
        <a href="{{ route('imports.index') }}" class="nav-link {{ request()->routeIs('imports.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-right"></i> Quản lý nhập kho
        </a>


        <a href="{{ route('exports.index') }}" class="nav-link {{ request()->routeIs('exports.*') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-up-right"></i> Quản lý xuất kho
        </a>

        <div class="mt-auto mb-3 px-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" 
                onclick="event.preventDefault(); this.closest('form').submit();" 
                class="text-danger border border-danger text-decoration-none d-flex align-items-center p-2 rounded">
                    <i class="bi bi-power me-2"></i> Đăng xuất
                </a>
            </form>
        </div>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-expand-lg bg-white px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-brand fw-bold text-primary">
                    Hệ thống Quản lý Linh kiện Điện tử
                </span>
                
                <div class="d-flex align-items-center">
                    <span class="me-2 text-secondary">Quản lý</span>
                    <i class="bi bi-person-circle fs-4 text-secondary"></i>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>