@extends('layouts.app')
@section('title', 'Thêm nhà cung cấp')

@section('content')
<div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-sm">
                <h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Thêm nhà cung cấp mới</strong></h3>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('nha-cung-caps.store') }}" method="POST">
                    @csrf
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ma_NCC">Mã</label>
                        <input type="text" name="ma_NCC" value="{{ $nextMaNCC }}" class="form-control" readonly> 
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="ten_NCC">Tên nhà cung cấp</label>
                        <input type="text" name="ten_NCC" class="form-control" required> 
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="SDT">Số điện thoại</label>
                        <input type="text" name="SDT" class="form-control" required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <label class="input-group-text" for="dia_chi">Địa chỉ</label>
                        <input type="text" name="dia_chi" class="form-control" required>
                    </div>
                    <div class="input-group mt-3 mb-3">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <a href="{{ route('nha-cung-caps.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection