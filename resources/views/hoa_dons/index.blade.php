@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2><b>Quản lý hóa đơn</b></h2>
                    </div>
                    
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('hoa-dons.create') }}" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Thêm hóa đơn mới</span></a>
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('hoa-dons.index') }}">
            <div class="input-group">
                    <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="Tìm kiếm hóa đơn...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Mã HD</th>
                        <th>Mã NV</th>
                        <th>Mã KH</th>
                        <th>Ngày tạo</th>
                        <th>Tổng tiền</th>
                        <th>Chi tiết</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hoaDons as $hd)
                        <tr>
                            <td>{{ $hd->ma_HD }}</td>
                            <td>{{ $hd->nhanVien->ma_NV ?? 'N/A' }}</td>
                            <td>{{ $hd->khachHang->ma_KH ?? 'N/A' }}</td>
                            <td>{{ $hd->ngay_tao }}</td>
                            <td>{{ number_format($hd->tong_tien, 2) }}</td>
                            <td>
                                <a href="{{ route('hoa_don.show', $hd->ma_HD) }}">Xem Chi Tiết</a>
                            </td>
                            <td>
                                <form action="{{ route('hoa-dons.destroy', $hd->ma_HD) }}" method="POST" style="display:inline-block;">
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
                {{ $hoaDons->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection