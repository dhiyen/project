@extends('layouts.app')

@section('content')
<div class="container-xl">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6 text-left">
						<h2><b>Chi tiết phiếu nhập</b></h2>
					</div>
				</div>
			</div>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Tên NCC</th>
                <th>Mã Thuốc</th>
                <th>Tên thuốc</th>
                <th>Thương hiệu</th>
                <th>Số Lượng Nhập</th>
                <th>HSD</th>
                <th>Thành Tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chiTietNhapHang as $chiTiet)
                <tr>
                    <td>
                        @if ($chiTiet->thuoc->nhacungcap->isNotEmpty())
                            {{ $chiTiet->thuoc->nhacungcap->first()->ten_NCC }}
                        @else
                            Không xác định
                        @endif
                    </td>
                    <td>{{ $chiTiet->ma_thuoc }}</td>
                    <td>{{ $chiTiet->thuoc->ten_thuoc }}</td>
                    <td>{{ $chiTiet->thuoc->thuong_hieu }}</td>
                    <td>{{ $chiTiet->so_luong_nhap }}</td>
                    <td>{{ $chiTiet->thuoc->HSD }}</td>
                    <td>{{ number_format($chiTiet->so_luong_nhap * $chiTiet->thuoc->gia_nhap, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="input-group mt-3 mb-3">
        <a href="{{ route('phieu-nhaps.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
    </div>
</div>
@endsection
