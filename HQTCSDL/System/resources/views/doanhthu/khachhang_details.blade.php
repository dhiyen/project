@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2><b>Chi tiết Khách Hàng</b></h2>
                    </div>
                </div>
            </div>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Tên</th>
                        <th>Số điện thoại</th>
                        <th>Giới tính</th>
                        <th>Ngày sinh</th>
                        <th>Điểm tích</th>
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
                    </tr>
                    @endforeach
        </tbody>
        </table>
        <div class="input-group mt-3 mb-3">
            <a href="{{ route('doanhthu.thongke') }}" class="btn btn-secondary ms-2">Trở lại</a>
        </div>
</div>
@endsection
