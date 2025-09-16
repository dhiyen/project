@extends('layouts.app')

@section('title', 'Thuốc của Nhà Cung Cấp')

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2><b>Thuốc của {{ $nhaCungCap->ten_NCC }}</b></h2>
                    </div>
                </div>
            </div>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Tên Thuốc</th>
                    <th>Giá Bán</th>
                    <th>Số lượng Tồn</th>
                </tr>
            </thead>
            <tbody>
                @foreach($thuocs as $thuoc)
                    <tr>
                        <td>{{ $thuoc->ma_thuoc }}</td>
                        <td>{{ $thuoc->ten_thuoc }}</td>
                        <td>{{ number_format($thuoc->gia_ban, 0, ',', '.') }} VNĐ</td>
                        <td>{{ $thuoc->so_luong_ton }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="color:black"><strong>Số loại thuốc: {{ $soLoaiThuoc }}</strong></p>
        <p style="color:black"><strong>Tổng giá bán: {{ number_format($tongGiaBan, 0, ',', '.') }} VNĐ</strong></p>
        <div class="input-group mt-3 mb-3">
            <a href="{{ route('nha-cung-caps.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
        </div>
    </div>
</div>
</div>
@endsection
