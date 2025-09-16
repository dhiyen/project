<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HoaDon; 
use App\Models\Thuoc;
use App\Models\NhanVien; 
use App\Models\KhachHang; 
use App\Models\ChiTietHD; 
use Illuminate\Support\Facades\DB;

class HoaDonController
{
    public function index(Request $request)
{
    $query = $request->get('query');
    $hoaDons = HoaDon::with(['khachHang', 'nhanVien']) 
        ->when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where(function ($q) use ($query) {
                $q->where('ma_KH', 'like', "%{$query}%");
            });
        })
        ->paginate(10);

    foreach ($hoaDons as $hoaDon) {
        $hoaDon->tong_tien = $this->getTotalAmountForInvoice($hoaDon->ma_HD);
    }
    return view('hoa_dons.index', compact('hoaDons','query'));
}
public function getTotalAmountForInvoice($ma_HD)
{
    $result = DB::select('SELECT dbo.TinhTongHoaDon(?) AS tong_tien', [$ma_HD]);
    return $result[0]->tong_tien ?? 0;
}
public function showDetails($ma_HD)
{
    $chiTietHoaDonView = DB::table('ChiTietHoaDonView')
                            ->where('ma_HD', $ma_HD)
                            ->get();
    return view('hoa_dons.details', compact('chiTietHoaDonView'));
}

    public function create()
    {
        $maxMaHD = DB::table('HoaDon')->max('ma_HD'); 
        $nextMaHD = $maxMaHD ? $maxMaHD + 1 : 1; 
        $nhanViens = NhanVien::all(); 
        $khachHangs = KhachHang::all(); 
        return view('hoa_dons.create', compact('nhanViens', 'khachHangs', 'nextMaHD'));
    }
public function store(Request $request)
{
    $request->validate([
        'ma_NV' => 'required',
        'ma_KH' => 'required',
        'ngay_tao' => 'required|date',
        'chiTietHD.*.ma_Thuoc' => 'required|integer',
        'chiTietHD.*.so_luong' => 'required|integer',
        'diem_doi' => 'nullable|integer|min:0',
    ]);

    DB::table('HoaDon')->insert([
        'ma_HD' => $request->ma_HD,
        'ma_NV' => $request->ma_NV,
        'ma_KH' => $request->ma_KH,
        'ngay_tao' => $request->ngay_tao,
    ]);

    $tongTien = 0;
    foreach ($request->chiTietHD as $chiTiet) {
        $thuoc = Thuoc::find($chiTiet['ma_Thuoc']);
        if ($thuoc) {
            if ($thuoc->so_luong_ton < $chiTiet['so_luong']) {
                return redirect()->back()->withErrors("Không đủ số lượng thuốc {$thuoc->ten_thuoc} để bán.");
            }
            $tongTien += $chiTiet['so_luong'] * $thuoc->gia_ban;
        }
    }
    
    $diem = floor($tongTien / 50000); 
    $khachHang = KhachHang::find($request->ma_KH);
    if ($khachHang) {
        $khachHang->diem_tich += $diem;
        $khachHang->save(); 
    }

    // Xử lý điểm đổi
    $diemDoi = $request->diem_doi ?? 0;
    $tienDoi = $diemDoi * 1000;

    if ($tienDoi > 0) {
        if ($khachHang->diem_tich >= $diemDoi) {
            $khachHang->diem_tich -= $diemDoi; 
            $khachHang->save();
            $tongTien -= $tienDoi;
        } else {
            return redirect()->back()->withErrors('Không đủ điểm để đổi.');
        }
    }

    // Lấy mã hóa đơn mới tạo
    $ma_HD = DB::table('HoaDon')->latest('ma_HD')->first()->ma_HD;

    // Lưu chi tiết hóa đơn
    foreach ($request->chiTietHD as $chiTiet) {
        DB::table('ChiTietHD')->insert([
            'ma_HD' => $ma_HD,
            'ma_Thuoc' => $chiTiet['ma_Thuoc'],
            'so_luong' => $chiTiet['so_luong'],
        ]);

        $thuoc = Thuoc::find($chiTiet['ma_Thuoc']);
        if ($thuoc) {
            $thuoc->so_luong_ton -= $chiTiet['so_luong']; 
            $thuoc->save(); 
        }
    }
    return redirect()->route('hoa-dons.index')->with('success', 'Hóa đơn đã được thêm thành công!');
}

    public function destroy(string $id)
    {
        ChiTietHD::where('ma_HD', $id)->delete();
        $hoadon = HoaDon::findOrFail($id);
        $hoadon->delete();

        return redirect()->route('hoa-dons.index')->with('success', 'Hóa đơn đã được xóa thành công!');
    }
}
