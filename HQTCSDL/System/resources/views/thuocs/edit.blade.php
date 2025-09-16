@extends('layouts.app')
@section('title', 'cập nhật thuốc')

@section('content')
<div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-sm">
                <h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Cập nhật thuốc</strong></h3>
                <form action="{{ route('thuoc.update', $thuoc->ma_thuoc) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ten_thuoc">Tên thuốc</label>
                        <input type="text" name="ten_thuoc" class="form-control" required value="{{ $thuoc->ten_thuoc }}"> 
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="thuong_hieu">Thương hiệu</label>
                        <input type="text" name="thuong_hieu" class="form-control" required value="{{ $thuoc->thuong_hieu }}">
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="lieu_luong">Liều lượng</label>
                        <input type="text" name="lieu_luong" class="form-control" required value="{{ $thuoc->lieu_luong }}">
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="so_luong_ton">Số lượng tồn</label>
                        <input type="number" name="so_luong_ton" class="form-control" required value="{{ $thuoc->so_luong_ton }}">
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="gia_nhap">Giá nhập</label>
                        <input type="text" name="gia_nhap" class="form-control" required value="{{ $thuoc->gia_nhap }}">
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="gia_ban">Giá bán</label>
                        <input type="text" name="gia_ban" class="form-control" required value="{{ $thuoc->gia_ban }}">
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="HSD">HSD</label>
                        <input type="date" name="HSD" class="form-control" required value="{{ $thuoc->HSD }}">
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('thuoc.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection