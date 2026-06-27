<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            overflow-x:hidden;
        }

        .sidebar{
            width:250px;
            min-height:100vh;
            background:#343a40;
        }

        .sidebar a{
            color:white;
            text-decoration:none;
            display:block;
            padding:12px;
        }

        .sidebar a:hover{
            background:#495057;
        }
    </style>

</head>
<body>

<div class="d-flex">

    <div class="sidebar">

        <h4 class="text-white text-center py-3">
            Admin
        </h4>

        <a href="/dashboard">Dashboard</a>

        <a href="#">Danh mục</a>

        <a href="#">Nhà cung cấp</a>

        <a href="#">Linh kiện</a>

        <a href="#">Nhập kho</a>

        <a href="#">Xuất kho</a>

        <a href="/logout">Đăng xuất</a>

    </div>

    <div class="flex-grow-1">

        <nav class="navbar bg-primary">

            <div class="container-fluid">

                <span class="navbar-brand text-white">
                    Hệ thống quản lý linh kiện điện tử
                </span>

            </div>

        </nav>

        <div class="container mt-4">

            @yield('content')

        </div>

    </div>

</div>

</body>
</html>