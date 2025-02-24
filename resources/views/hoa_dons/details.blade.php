@extends('layouts.app')

@section('content')
<div class="container-xl">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6 text-left">
						<h2><b>Chi tiết hóa đơn</b></h2>
					</div>
				</div>
			</div>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Tên thuốc</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                @if ($chiTietHoaDonView->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">Không có dữ liệu chi tiết hóa đơn.</td>
                    </tr>
                @else
                    @foreach ($chiTietHoaDonView as $chiTiet)
                    <tr>
                    <td>{{ $chiTiet->ten_thuoc_view }}</td>
                        <td>{{ $chiTiet->so_luong }}</td>
                        <td>{{ number_format($chiTiet->gia_ban ?? 0, 2) }}</td>
                        <td>{{ number_format($chiTiet->thanh_tien ?? 0, 2) }}</td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <div class="input-group mt-3 mb-3">
                <a href="{{ route('hoa-dons.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
            </div>
        </div>
    </div>
</div>
@endsection