@extends('layouts.app')
@section('title', 'Thêm khách hàng')

@section('content')


<div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-sm">
                <h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Thêm Khách hàng</strong></h3>
                @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                <form action="{{ route('khach-hangs.store') }}" method="POST">
                @csrf
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ten_KH">Tên</label>
                        <input type="text" name="ten_KH" class="form-control"required> 
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="SDT_Kh">Số điện thoại</label>
                        <input type="text" name="SDT_KH" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="gioi_tinh">Giới tính</label>
                        <input type="text" name="gioi_tinh" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ngay_sinh">Ngày sinh</label>
                        <input type="date" name="ngay_sinh" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="diem_tich">Điểm tích</label>
                        <input type="number" name="diem_tich" class="form-control"required>
                    </div>
                    
                    <div class="input-group mt-3 mb-3">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <a href="{{ route('khach-hangs.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection