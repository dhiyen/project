@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Tạo phiếu nhập mới</strong></h3>
    <form action="{{ route('phieu-nhaps.store') }}" method="POST">
        @csrf
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
                    <label class="input-group-text" for="ma_PN">Mã Phiếu Nhập</label>
                    <input type="text" name="ma_PN" value="{{ $nextMaPN }}" class="form-control" readonly>
                </div>
                <div class="input-group mt-3 mb-3">
                    <label for="ngay_dat" class="input-group-text">Ngày Đặt:</label>
                    <input type="date" name="ngay_dat" class="form-control" required>
                </div>
                <div class="input-group mt-3 mb-3">
                    <label for="ngay_nhan" class="input-group-text">Ngày Nhận:</label>
                    <input type="date" name="ngay_nhan" class="form-control" required>
                </div>
            <div id="thuoc-container">
                <div class="thuoc-item mb-3">
                    <label for="thuoc[0][ma_thuoc]" class="input-group-text">Chọn Thuốc:</label>
                    <select name="thuoc[0][ma_thuoc]" class="form-control" required>
                        <option value="">Chọn thuốc</option>
                            @foreach ($thuocs as $thuoc)
                                <option value="{{ $thuoc->ma_thuoc }}">
                                {{ $thuoc->ten_thuoc }} 
                                (Nhà Cung Cấp: 
                                @foreach($thuoc->nhacungcap as $nhaCungCap) 
                                {{ $nhaCungCap->ten_NCC }} 
                            @endforeach)
                        </option>
                         @endforeach
                    </select>
                    <input type="number" name="thuoc[0][so_luong]" class="form-control mt-2" placeholder="Số lượng" required min="1">
                </div>
            </div>
            <button type="button" class="btn btn-success mb-2" id="add-thuoc">Thêm thuốc</button>
            <div>
                <button type="submit" class="btn btn-primary">Lưu Phiếu Nhập</button>
                <a href="{{ route('phieu-nhaps.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
            </div>
    </form>
</div>
<script>
    document.getElementById('add-thuoc').addEventListener('click', function() {
        const container = document.getElementById('thuoc-container');
        const index = container.children.length; 
        const newItem = document.createElement('div');
        newItem.classList.add('thuoc-item', 'mb-3');
        newItem.innerHTML = `
            <label for="thuoc[${index}][ma_thuoc]" class="form-label">Chọn Thuốc:</label>
            <select name="thuoc[${index}][ma_thuoc]" class="form-control" required>
                <option value="">Chọn thuốc</option>
                @foreach ($thuocs as $thuoc)
                    <option value="{{ $thuoc->ma_thuoc }}">
                        {{ $thuoc->ten_thuoc }} 
                        (Nhà Cung Cấp: 
                        @foreach($thuoc->nhacungcap as $nhaCungCap) 
                            {{ $nhaCungCap->ten_NCC }} 
                        @endforeach)
                    </option>
                @endforeach
            </select>
            <input type="number" name="thuoc[${index}][so_luong]" class="form-control mt-2" placeholder="Số lượng" required min="1">
        `;
        container.appendChild(newItem);
    });
</script>
@endsection
