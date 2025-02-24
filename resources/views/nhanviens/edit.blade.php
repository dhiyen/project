@extends('layouts.app')
@section('title', 'Cập nhật nhân viên')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-sm">
            <h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Cập nhật nhân viên</strong></h3>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('nhan-viens.update', $nhanvien->ma_NV) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="input-group mt-3 mb-3">
                    <label class="input-group-text" for="ten_NV">Tên</label>
                    <input type="text" name="ten_NV" class="form-control" required value="{{ $nhanvien->ten_NV }}"> 
                </div>
                <div class="input-group mt-3 mb-3">
                    <label class="input-group-text" for="SDT">Số điện thoại</label>
                    <input type="text" name="SDT" class="form-control" required value="{{ $nhanvien->SDT }}">
                </div>
                <div class="input-group mt-3 mb-3">
                    <label class="input-group-text" for="dia_chi">Địa chỉ</label>
                    <input type="text" name="dia_chi" class="form-control" required value="{{ $nhanvien->dia_chi }}">
                </div>
                <div class="input-group mt-3 mb-3">
                    <label class="input-group-text" for="ngay_sinh">Ngày sinh</label>
                    <input type="date" name="ngay_sinh" class="form-control" required value="{{ $nhanvien->ngay_sinh }}">
                </div>
                <div class="input-group mt-3 mb-3">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('nhan-viens.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
