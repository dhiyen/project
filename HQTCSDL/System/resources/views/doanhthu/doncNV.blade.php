@extends('layouts.app') 

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8 text-left">
                        <h2><b>Thống Kê Số Lượng Đơn Hàng Của Nhân Viên</b></h2>
                    </div>
                </div>
            </div>
            @if($donhang->isEmpty())
                <div class="alert alert-warning">Không có dữ liệu.</div>
            @else
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Mã </th>
                        <th>Tên NV</th>
                        <th>Số Lượng Đơn Hàng</th>
                        <th>Tổng doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donhang as $item)
                        <tr>
                            <td>{{ $item->ma_NV }}</td>
                            <td>{{ $item->ten_NV }}</td>
                            <td>{{ $item->so_hoa_don }}</td>
                            <td>{{ number_format($item->TongDoanhThu, 0, ',', '.') }} VND</td>                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
     @endif
    </div>
</div>
@endsection
