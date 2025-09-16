@extends('layouts.app')

@section('title', 'Báo Cáo Tồn Kho')

@section('content')
<div class="container mt-5 mb-5">
    <h3 class="text-center text-uppercase" style="color: #363636;"><strong>Báo Cáo Tồn Kho</strong></h3>

    <!-- <div class="mt-4 bg-light p-4 rounded shadow" style="color: black;">
        <h4 class="text-primary"><strong>Tổng số sản phẩm thuốc:</strong> <span class="fw-bold">{{ $tongSoSanPham }}</span></h4>
    </div> -->

    <div class="mt-4 bg-light p-4 rounded shadow">
        <h4 class="text-danger"><strong>Thuốc sắp hết hạn:</strong></h4>
        @if($thuocSapHetHan->isEmpty())
            <p class="text-muted"style="color: black;">Không có thuốc nào sắp hết hạn.</p>
        @else
            <ul class="list-group" style="color: black;">
                @foreach($thuocSapHetHan as $thuoc)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $thuoc->ten_thuoc }}</strong><br>
                            <small>- Số lượng: {{$thuoc->so_luong_ton}} | HSD: {{ $thuoc->HSD }}</small>
                        </div>
                        </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="mt-4 bg-light p-4 rounded shadow">
        <h4 class="text-success"><strong>Thuốc tồn kho thấp:</strong></h4>
        @if($thuocTonThap->isEmpty())
            <p class="text-muted">Không có thuốc nào tồn kho thấp.</p>
        @else
            <ul class="list-group" style="color: black;">
                @foreach($thuocTonThap as $thuoc)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $thuoc->ten_thuoc }}</strong><br>
                            <small>- Số lượng tồn: {{ $thuoc->so_luong_ton }}</small>
                        </div>
                        <span class="badge bg-danger text-white">Cảnh báo!</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
