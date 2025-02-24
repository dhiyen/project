<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NhaCungCapController;
use App\Http\Controllers\ThuocController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\PhieuNhapController;
use App\Http\Controllers\DoanhthuController;
use App\Http\Controllers\LichSuMuaController;
use Illuminate\Support\Facades\DB;

Route::get('/test-db', function () {
    $results = DB::select('SELECT 1');
    dd($results);  
});
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth']);

Route::resource('nha-cung-caps', NhaCungCapController::class);
Route::get('nha-cung-caps/{id}/thuocs', [NhaCungCapController::class, 'showThuocs'])->name('nha-cung-caps.thuocs');
Route::get('nha-cung-cap/{maNCC}/thong-ke', [NhaCungCapController::class, 'showThongKeThuoc'])->name('nha-cung-caps.thong-ke');

Route::resource('thuoc', ThuocController::class);
Route::get('/thuocs', [ThuocController::class, 'index'])->name('thuoc.index');
Route::get('/thuoc/so-luong-theo-thuong-hieu', [ThuocController::class, 'getThuocTheoThuongHieu'])->name('thuoc.soLuongTheoThuongHieu');
Route::resource('nhan-viens', NhanVienController::class);
Route::get('/nhan-viens/{id}/hoa-don', [NhanVienController::class, 'showHoaDon'])->name('nhan-viens.hoa-don');

Route::resource('khach-hangs', KhachHangController::class);
Route::get('/lich-su-mua', [LichSuMuaController::class, 'index'])->name('lich-su-mua.index');
Route::get('/khach-hangs/lich-su-mua', [KhachHangController::class, 'show'])->name('khach-hangs.show');

Route::resource('hoa-dons', HoaDonController::class);
Route::get('/hoa-don/{ma_HD}', [HoaDonController::class, 'showDetails'])->name('hoa_don.show');
Route::get('/hoa-dons/lookup-by-phone', [HoaDonController::class, 'lookupByPhone'])->name('hoa-dons.lookupByPhone');

Route::resource('phieu-nhaps', PhieuNhapController::class);
Route::get('phieu-nhap/{ma_PN}/details', [PhieuNhapController::class, 'showDetails'])->name('phieu-nhap.details');

Route::get('/doanh-thu', [DoanhthuController::class, 'thongKe'])->name('doanhthu.thongke');
Route::get('/doanhthu/report', [DoanhthuController::class, 'report'])->name('doanhthu.report');
Route::get('/so-luong-don', [DoanhthuController::class, 'getSLDonCuaNVData'])->name('soLuongDon');
Route::get('/khach-hang/chi-tiet/{month}/{year}', [DoanhthuController::class, 'showKhachHangDetails'])->name('khachhang.details');
