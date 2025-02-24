@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2><b>Hóa Đơn Của Khách Hàng</b></h2>
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('khach-hangs.show') }}" class="mb-3">
                <div class="input-group">
                    <input type="number" name="thang" value="{{ request('thang') }}" class="form-control" placeholder="Chọn tháng" min="1" max="12">
                    <input type="number" name="nam" value="{{ request('nam') }}" class="form-control" placeholder="Chọn năm" min="1900" max="{{ date('Y') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Tên Khách Hàng</th>
                        <th>Ngày Tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @if($paginatedResults->count() > 0)
                        @foreach($paginatedResults as $khachHang)
                            <tr>
                                <td>{{ $khachHang->ma_KH }}</td>
                                <td>{{ $khachHang->ten_KH }}</td>
                                <td>{{ \Carbon\Carbon::parse($khachHang->ngay_tao)->format('d-m-Y') }}</td> <!-- Định dạng ngày -->
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">Không có kết quả nào.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $paginatedResults->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
