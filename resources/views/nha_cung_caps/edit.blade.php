@extends('layouts.app')
@section('title', 'cập nhật nhà cung cấp')

@section('content')
<div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-sm">
                <h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Cập nhật nhà cung cấp</strong></h3>
                <form action="{{ route('nha-cung-caps.update', $nhaCungCap->ma_NCC) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ten_NCC">Tên nhà cung cấp</label>
                        <input type="text" name="ten_NCC" class="form-control" required value="{{ $nhaCungCap->ten_NCC }}"> 
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="SDT">Số điện thoại</label>
                        <input type="text" name="SDT" class="form-control" required value="{{ $nhaCungCap->SDT }}">
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="dia_chi">Địa chỉ</label>
                        <input type="text" name="dia_chi" class="form-control" required value="{{ $nhaCungCap->dia_chi }}">
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('nha-cung-caps.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection