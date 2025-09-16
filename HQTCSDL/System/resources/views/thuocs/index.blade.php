@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2><b>Quản lý Thuốc</b></h2>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('thuoc.create') }}" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Thêm thuốc mới</span></a>
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form method="GET" action="{{ route('thuoc.index') }}">
            <div class="input-group">
                    <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="Tìm kiếm thuốc...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Tên Thuốc</th>
                        <th>Thương Hiệu</th>
                        <th>Liều Lượng</th>
                        <th>Số Lượng Tồn</th>
                        <th>Giá Nhập</th>
                        <th>Giá Bán</th>
                        <th>HSD</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($thuocs as $thuoc)
                    <tr>
                        <td>{{ $thuoc->ma_thuoc }}</td>
                        <td>{{ $thuoc->ten_thuoc }}</td>
                        <td>{{ $thuoc->thuong_hieu }}</td>
                        <td>{{ $thuoc->lieu_luong }}</td>
                        <td>{{ $thuoc->so_luong_ton }}</td>
                        <td>{{ number_format($thuoc->gia_nhap, 2) }} VNĐ</td>
                        <td>{{ number_format($thuoc->gia_ban, 2) }} VNĐ</td>
                        <td>{{ \Carbon\Carbon::parse($thuoc->HSD)->format('d/m/Y') }}</td>
                        <td>
                        <div class="btn-group" role="group" aria-label="User Actions">
                            <a href="{{ route('thuoc.edit', $thuoc->ma_thuoc) }}" class="btn btn-outline-warning btn-custom-size"> <i class="bi bi-pencil pencil-icon"></i></a>
                            <form action="{{ route('thuoc.destroy', $thuoc->ma_thuoc) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-custom-size"><i class="bi bi-trash trash-icon"></i></button>
                            </form>
                        </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $thuocs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
