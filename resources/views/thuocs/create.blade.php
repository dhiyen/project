@extends('layouts.app')
@section('title', 'Thêm thuốc')

@section('content')
<div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-sm">
                <h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Thêm thuốc mới</strong></h3>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                 @endif
                <form action="{{ route('thuoc.store') }}" method="POST">
                @csrf
                    <div class="input-group mt-3 mb-3">
                         <label class="input-group-text" for="ma_thuoc">Mã thuốc</label>
                         <input type="text" name="ma_thuoc" class="form-control" value="{{ $nextMaThuoc }}" readonly> 
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ten_thuoc">Tên thuốc</label>
                        <input type="text" name="ten_thuoc" class="form-control"required> 
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="thuong_hieu">Thương hiệu</label>
                        <input type="text" name="thuong_hieu" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="lieu_luong">Liều lượng</label>
                        <input type="text" name="lieu_luong" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="so_luong_ton">Số lượng tồn</label>
                        <input type="number" name="so_luong_ton" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="gia_nhap">Giá nhập</label>
                        <input type="text" name="gia_nhap" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="gia_ban">Giá bán</label>
                        <input type="text" name="gia_ban" class="form-control"required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="HSD">HSD</label>
                        <input type="date" name="HSD" class="form-control"required>
                    </div>
                    <div class="form-group">
                        <label for="ma_NCC">Nhà Cung Cấp</label>
                        <select name="ma_NCC" id="ma_NCC" class="form-control">
                            @foreach($nhaCungCaps as $ncc)
                                <option value="{{ $ncc->ma_NCC }}">{{ $ncc->ten_NCC }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <a href="{{ route('thuoc.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection