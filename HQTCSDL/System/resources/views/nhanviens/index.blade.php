@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2><b>Quản lý Nhân viên</b></h2>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('nhan-viens.create') }}" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Thêm nhân viên mới</span></a>
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form method="GET" action="{{ route('nhan-viens.index') }}">
            <div class="input-group">
                    <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="Tìm kiếm nhân viên...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Tên</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ngày sinh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nhanViens as $nv)
                    <tr>
                        <td>{{ $nv->ma_NV }}</td>
                        <td>{{ $nv->ten_NV }}</td>
                        <td>{{ $nv->SDT }}</td>
                        <td>{{ $nv->dia_chi }}</td>
                        <td>{{ $nv->ngay_sinh }}</td>
                        <td>
                        <div class="btn-group" role="group" aria-label="User Actions">
                            <a href="{{ route('nhan-viens.edit', $nv->ma_NV) }}" class="btn btn-outline-warning btn-custom-size"> <i class="bi bi-pencil pencil-icon"></i></a>
                            <form action="{{ route('nhan-viens.destroy', $nv->ma_NV) }}" method="POST" style="display:inline-block;">
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
                {{ $nhanViens->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
