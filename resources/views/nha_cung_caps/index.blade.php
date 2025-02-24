@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <h2><b>Quản lý Nhà Cung Cấp</b></h2>
                    </div>
                    <div class="col-sm-6 text-right">    
                       <a href="{{ route('nha-cung-caps.create') }}" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Thêm nhà cung cấp mới</span></a>
                       </div>
                </div>
            </div>
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" action="{{ route('nha-cung-caps.index') }}">
            <div class="input-group">
                    <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="Tìm kiếm thuốc...">
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
                    <th>SĐT</th>
                    <th>Địa Chỉ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nhaCungCaps as $ncc)
                    <tr style="color:black">
                        <td>{{ $ncc->ma_NCC }}</td>
                        <td>
                            <a href="{{ route('nha-cung-caps.thuocs', $ncc->ma_NCC) }}" >{{ $ncc->ten_NCC }}</a>
                        </td>
                        <td>{{ $ncc->SDT }}</td>
                        <td>{{ $ncc->dia_chi }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="User Actions">
                                <a href="{{ route('nha-cung-caps.edit', $ncc->ma_NCC) }}" class="btn btn-outline-warning btn-custom-size"><i class="bi bi-pencil pencil-icon"></i></a>
                                <form action="{{ route('nha-cung-caps.destroy', $ncc->ma_NCC) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-custom-size"><i class="bi bi-trash trash-icon"></i></butto>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h5 style="color:black">Trung Bình Số Loại Thuốc Cung Cấp: {{ number_format($trungBinhSoLoaiThuoc, 2) }}</h5>
        <div class="d-flex justify-content-center">
            {{ $nhaCungCaps->links('pagination::bootstrap-4') }}
        </div>
        </div>
    </div>
</div>
@endsection