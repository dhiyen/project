@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2><b>Quản lý Khách Hàng</b></h2>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('khach-hangs.create') }}" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Thêm khách hàng mới</span></a>
                        <a href="{{ route('khach-hangs.show') }}" class="btn btn-info"><i class="fa fa-shopping-cart"></i><span>Hóa đơn</span></a>   
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="GET" action="{{ route('khach-hangs.index') }}">
            <div class="input-group">
                    <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="Tìm kiếm khách hàng...">
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
                        <th>Giới tính</th>
                        <th>Ngày sinh</th>
                        <th>Điểm tích</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($khachHangs as $khachhang)
                    <tr>
                        <td>{{ $khachhang->ma_KH }}</td>
                        <td>{{ $khachhang->ten_KH }}</td>
                        <td>{{ $khachhang->SDT_KH }}</td>
                        <td>{{ $khachhang->gioi_tinh }}</td>
                        <td>{{ $khachhang->ngay_sinh }}</td>
                        <td>{{ $khachhang->diem_tich }}</td>
                        <td>
                        <div class="btn-group" role="group" aria-label="User Actions">
                        <a href="{{ route('khach-hangs.edit', $khachhang->ma_KH) }}" class="btn btn-outline-warning btn-custom-size"> <i class="bi bi-pencil pencil-icon"></i></a>
                        <form action="{{ route('khach-hangs.destroy', $khachhang->ma_KH) }}" method="POST" style="display:inline-block;">
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
                {{ $khachHangs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
