@extends('layouts.app')
@section('title', 'Hóa Đơn Của Nhân Viên')

@section('content')
<div class="container">
    <h1 class="text-center">Danh Sách Hóa Đơn Của Nhân Viên</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Mã NV</th>
                <th>Tên NV</th>
                <th>Số Hóa Đơn</th>
                <th>Tổng Tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hoaDons as $hoaDon)
                <tr>
                    <td>{{ $hoaDon->ma_NV }}</td>
                    <td>{{ $hoaDon->ten_NV }}</td>
                    <td>{{ $hoaDon->so_hoa_don }}</td>
                    <td>{{ number_format($hoaDon->TongTien, 0, ',', '.') }} VNĐ</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
