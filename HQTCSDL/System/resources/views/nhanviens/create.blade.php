@extends('layouts.app')
@section('title', 'Thêm thuốc')

@section('content')
<div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-sm">
                <h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Thêm nhân viên mới</strong></h3>
                <form action="{{ route('nhan-viens.store') }}" method="POST">
                @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ma_NV">Mã</label>
                        <input type="text" name="ma_NV" value="{{ $nextMaNV }}" class="form-control" readonly> 
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ten_NV">Tên</label>
                        <input type="text" name="ten_NV" class="form-control"required> 
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="SDT">Số điện thoại</label>
                        <input type="text" name="SDT" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="dia_chi">Địa chỉ</label>
                        <input type="text" name="dia_chi" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ngay_sinh">Ngày sinh</label>
                        <input type="date" name="ngay_sinh" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <a href="{{ route('nhan-viens.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection