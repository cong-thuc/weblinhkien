@extends('layout.app')

@section('title','Dashboard')

@section('content')

<h2>Dashboard</h2>

<div class="row">

    <div class="col-md-3">

        <div class="card bg-primary text-white">

            <div class="card-body">

                <h5>Danh mục</h5>

                <h2>0</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card bg-success text-white">

            <div class="card-body">

                <h5>Nhà cung cấp</h5>

                <h2>0</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card bg-warning text-white">

            <div class="card-body">

                <h5>Linh kiện</h5>

                <h2>0</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card bg-danger text-white">

            <div class="card-body">

                <h5>Xuất kho</h5>

                <h2>0</h2>

            </div>

        </div>

    </div>

</div>

@endsection