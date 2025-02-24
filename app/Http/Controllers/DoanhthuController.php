<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HoaDon;
use App\Models\ChiTietHD;
use App\Models\Thuoc;
use App\Models\KhachHang;
use Carbon\Carbon;

class DoanhthuController
{
    public function getSLDonCuaNVData()
{
    $donhang = DB::table('v_SLDonCuaNV')
        ->join('HoaDon', 'HoaDon.ma_NV', '=', 'v_SLDonCuaNV.ma_NV')
        ->select(
            'v_SLDonCuaNV.ma_NV',
            'v_SLDonCuaNV.ten_NV',
            'v_SLDonCuaNV.so_hoa_don',
            DB::raw('dbo.fn_TinhTongDoanhThuNhanVien(v_SLDonCuaNV.ma_NV) AS TongDoanhThu')
        )
        ->groupBy('v_SLDonCuaNV.ma_NV', 'v_SLDonCuaNV.ten_NV', 'v_SLDonCuaNV.so_hoa_don')
        ->get();
    return view('doanhthu.doncNV', compact('donhang'));
}
public function showThongKeThuoc($maNCC)
{
    $minSL = 0;
    $maxSL = 1000;

    if (request()->has('min_sl') && request()->has('max_sl')) {
        $minSL = (int) request()->input('min_sl');
        $maxSL = (int) request()->input('max_sl');
    }
    $thuocs = DB::table('NCC_Thuoc')
                ->join('Thuoc', 'NCC_Thuoc.ma_thuoc', '=', 'Thuoc.ma_thuoc')
                ->where('NCC_Thuoc.ma_NCC', $maNCC)
                ->paginate(10); 

    $nhaCungCapThongKe = DB::select('EXEC sp_SLThuocNCC ?, ?, ?', [$maNCC, $minSL, $maxSL]);

    if (empty($nhaCungCapThongKe)) {
        $nhaCungCapThongKe = null;
    }
    return view('nha_cung_caps.thuocs', compact('thuocs', 'nhaCungCapThongKe', 'maNCC'));
}
    public function thongKe(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month); 
        $year = $request->input('year', Carbon::now()->year); 
        
        $doanhThu = DB::table('HoaDon')
            ->join('ChiTietHD', 'HoaDon.ma_HD', '=', 'ChiTietHD.ma_HD')
            ->join('Thuoc', 'ChiTietHD.ma_Thuoc', '=', 'Thuoc.ma_thuoc')
            ->select(
                DB::raw('SUM(ChiTietHD.so_luong * Thuoc.gia_ban) as tong_tien'),
                DB::raw('COUNT(DISTINCT HoaDon.ma_HD) as so_don_hang'),
                'HoaDon.ma_KH'
            )
            ->whereMonth('HoaDon.ngay_tao', $month)
            ->whereYear('HoaDon.ngay_tao', $year)
            ->groupBy('HoaDon.ma_KH')
            ->get();
            
        // Tính tổng doanh thu, số khách hàng và số lượng đơn hàng
        $tongDoanhThu = $doanhThu->sum('tong_tien');
        $soKhachHang = $doanhThu->unique('ma_KH')->count(); 
        $soDonHang = $doanhThu->sum('so_don_hang'); 

        return view('doanhthu.thongke', compact('tongDoanhThu', 'soKhachHang', 'soDonHang', 'month', 'year'));
    }

    public function showKhachHangDetails($month, $year)
    {
        $khachHangs = DB::table('HoaDon')
            ->join('KhachHang', 'HoaDon.ma_KH', '=', 'KhachHang.ma_KH')
            ->select('KhachHang.*')
            ->whereMonth('HoaDon.ngay_tao', $month)
            ->whereYear('HoaDon.ngay_tao', $year)
            ->distinct() 
            ->get();
        return view('doanhthu.khachhang_details', compact('khachHangs', 'month', 'year'));
    }

    public function report()
    {
        $tongSoSanPham = Thuoc::count();
        $thuocSapHetHan = Thuoc::where('HSD', '<=', Carbon::now()->addDays(60))->get();
        $soLuongTonThap = 500;
        $thuocTonThap = Thuoc::where('so_luong_ton', '<', $soLuongTonThap)->get();
        return view('doanhthu.report', compact('tongSoSanPham', 'thuocSapHetHan', 'thuocTonThap'));
    }
}
