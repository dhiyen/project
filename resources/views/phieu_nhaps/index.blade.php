@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2><b>Quản lý nhập hàng</b></h2>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('phieu-nhaps.create') }}" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Thêm phiếu nhập mới</span></a>
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form method="GET" action="{{ route('phieu-nhaps.index') }}">
            <div class="input-group">
                    <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="Tìm kiếm phiếu nhập...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Mã PN</th>
                        <th>Tổng só lượng nhập</th>
                        <th>Ngày Đặt</th>
                        <th>Ngày Nhận</th>
                        <th>Tổng Tiền</th>
                        <th>Chi Tiết</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($phieuNhaps as $phieuNhap)
                        <tr>
                            <td>{{ $phieuNhap->ma_PN }}</td>
                            <td>{{ $phieuNhap->tong_so_luong_nhap }}</td>
                            <td>{{ $phieuNhap->ngay_dat }}</td>
                            <td>{{ $phieuNhap->ngay_nhan }}</td>
                            <td>{{ number_format($phieuNhap->tong_tien, 2) }} </td>
                            <td>
                                <a href="{{ route('phieu-nhap.details', $phieuNhap->ma_PN) }}">Xem Chi Tiết</a>
                            </td>
                            <td>
                                <form action="{{ route('phieu-nhaps.destroy', $phieuNhap->ma_PN) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-custom-size"><i class="bi bi-trash trash-icon"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $phieuNhaps->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection