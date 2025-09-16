@extends('layouts.app')

@section('content')
<div class="container">
<h3 class="text-center text-uppercase fw-bold" style="color: #363636;"><strong>Thống Kê Doanh Thu</strong></h3>

    <form method="GET" action="{{ route('doanhthu.thongke') }}" class="bg-light p-4 rounded shadow">
        <div class="table table-bordered text-center">
            <label for="month" class="form-label">Chọn Tháng:</label>
            <select name="month" class="form-select" required>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                        Tháng {{ $i }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="table table-bordered text-center">
            <label for="year" class="form-label">Chọn Năm:</label>
            <select name="year" class="form-select" required>
                @for($i = date('Y'); $i >= 2000; $i--)
                    <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                        Năm {{ $i }}
                    </option>
                @endfor
                </select>
        </div>
        <div class="input-group mt-3 mb-3 justify-content-center">
            <button type="submit" class="btn btn-primary">Thống kê</button>
        </div>
    </form>

    <div class="mt-4 bg-light p-3 rounded shadow">
        <h4><strong>Doanh thu tháng {{ $month }}/{{ $year }}:</strong></h4>
        <p class="fw-bold" style="color: Red;">Tổng doanh thu: {{ number_format($tongDoanhThu, 0, ',', '.') }} VNĐ</p>
        <p class="fw-bold" style="color: navy;">Số lượng đơn hàng: {{ $soDonHang }}</p>
        <p class="fw-bold" style="color: purple;">Số khách hàng: <a href="{{ route('khachhang.details', ['month' => $month, 'year' => $year]) }}">
            {{ $soKhachHang }}
        </a>
        </p>
    </div>
</div>
@endsection
