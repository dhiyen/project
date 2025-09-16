@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Tạo hóa đơn mới</strong></h3>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('hoa-dons.store') }}" method="POST">
        @csrf
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="ma_HD">Mã hóa đơn</label>
            <input type="text" name="ma_HD" value="{{ $nextMaHD }}" class="form-control" readonly> 
        </div>
        
        <div class="input-group mt-3 mb-3">
            <label for="ma_NV" class="input-group-text">Chọn Nhân Viên</label>
            <select name="ma_NV" id="ma_NV" class="form-control" required>
                @foreach ($nhanViens as $nhanVien)
                    <option value="{{ $nhanVien->ma_NV }}">{{ $nhanVien->ten_NV }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ma_KH" class="input-group-text">Chọn Khách Hàng</label>
            <select name="ma_KH" id="ma_KH" class="form-control" required>
                @foreach ($khachHangs as $khachHang)
                    <option value="{{ $khachHang->ma_KH }}">{{ $khachHang->ten_KH }}</option>
                @endforeach
            </select>
        </div>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="ngay_tao">Ngày Tạo</label>
            <input type="date" name="ngay_tao" class="form-control" required>
        </div>

        <div class="input-group mt-3 mb-3">
            <h4 class="input-group-text" style="color: black;">Chọn thuốc</h4>
            <div id="chiTietHD">
                <div class="row">
                    <div class="col">
                        <input type="number" name="chiTietHD[0][ma_Thuoc]" placeholder="Mã Thuốc" class="form-control" required>
                    </div>
                    <div class="col">
                        <input type="number" name="chiTietHD[0][so_luong]" placeholder="Số Lượng" class="form-control" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success mt-2" onclick="addChiTiet()">Thêm thuốc</button>
        </div>
        <div class="input-group mt-3 mb-3">
            <label for="diem_doi" class="input-group-text">Điểm Đổi:</labe>
            <input type="number" name="diem_doi" class="form-control" min="0" placeholder="Nhập điểm muốn đổi">
        </div>
        <div class="input-group mt-3 mb-3">
            <button type="submit" class="btn btn-primary">Thêm Hóa Đơn</button>
            <a href="{{ route('hoa-dons.index') }}" class="btn btn-secondary ms-2">Trở lại</a>
        </div>
    </form>

    <script>
    function addChiTiet() {
    const index = document.querySelectorAll('#chiTietHD .row').length;
    const newRow = `
        <div class="row">
            <div class="col">
                <input type="number" name="chiTietHD[${index}][ma_Thuoc]" placeholder="Mã Thuốc" class="form-control" required>
            </div>
            <div class="col">
                <input type="number" name="chiTietHD[${index}][so_luong]" placeholder="Số Lượng" class="form-control" required>
            </div>
        </div>
    `;
    document.getElementById('chiTietHD').insertAdjacentHTML('beforeend', newRow);
}
    </script>
</div>
@endsection
