@extends('layout.master')

@section('content')

<h2 class="mb-4">Quản lý Danh mục</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Form thêm danh mục -->
<div class="card mb-4">

    <div class="card-header bg-primary text-white">
        Thêm danh mục
    </div>

    <div class="card-body">

        <form action="{{ route('category.store') }}" method="POST">

            @csrf

            <div class="mb-3">
                <label class="form-label">Tên danh mục</label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="3"
                >{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">
                Thêm danh mục
            </button>

        </form>

    </div>

</div>

<!-- Danh sách -->
<table class="table table-bordered table-hover">

    <thead class="table-dark">

    <tr>

        <th width="70">ID</th>
        <th>Tên danh mục</th>
        <th>Mô tả</th>
        <th width="180">Thao tác</th>

    </tr>

    </thead>

    <tbody>

    @forelse($categories as $category)

        <tr>

            <td>{{ $category->id }}</td>

            <td>{{ $category->name }}</td>

            <td>{{ $category->description }}</td>

            <td>

                <a href="{{ route('category.edit', $category->id) }}"
                   class="btn btn-warning btn-sm">
                    Sửa
                </a>

                <form
                    action="{{ route('category.destroy', $category->id) }}"
                    method="POST"
                    style="display:inline"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Bạn có chắc muốn xóa?')">
                        Xóa
                    </button>

                </form>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="4" class="text-center">
                Chưa có dữ liệu
            </td>

        </tr>

    @endforelse

    </tbody>

</table>


@endsection