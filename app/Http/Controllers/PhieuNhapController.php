<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhieuNhap;
use App\Models\NhaCungCap; 
use App\Models\Thuoc;
use App\Models\ChiTietNhapHang;
use Illuminate\Support\Facades\DB;
class PhieuNhapController
{
    public function index()
    {
        $phieuNhaps = PhieuNhap::with('chitiet.thuoc.nhacungcap') 
            ->paginate(10);

        foreach ($phieuNhaps as $phieuNhap) {
            $phieuNhap->tong_so_luong_nhap = $phieuNhap->chitiet->sum('so_luong_nhap');
            $phieuNhap->tong_tien = $phieuNhap->chitiet->sum(function ($chiTiet) {
                return $chiTiet->so_luong_nhap * $chiTiet->thuoc->gia_nhap;
            });
        }

        return view('phieu_nhaps.index', compact('phieuNhaps'));
    }
    public function showDetails($ma_PN)
    {
        $chiTietNhapHang = ChiTietNhapHang::with('thuoc', 'thuoc.nhaCungCap')
            ->where('ma_PN', $ma_PN)
            ->get();
        
        return view('phieu_nhaps.details', compact('chiTietNhapHang'));
    }

    public function create()
    {
        $maxMaPN = DB::table('phieunhap')->max('ma_PN');
        $nextMaPN = $maxMaPN ? $maxMaPN + 1 : 1;
        $nhaCungCaps = NhaCungCap::all(); 
        $thuocs = Thuoc::all(); 
    
        return view('phieu_nhaps.create', compact('nhaCungCaps', 'thuocs', 'nextMaPN'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'ngay_dat' => 'required|date',
            'ngay_nhan' => 'required|date',
            'thuoc' => 'required|array',
            'thuoc.*.ma_thuoc' => 'required|exists:thuoc,ma_thuoc',
            'thuoc.*.so_luong' => 'required|integer|min:1',
        ]);

        DB::table('phieunhap')->insert([
            'ma_PN' => $request->ma_PN,  
            'ngay_dat' => $request->ngay_dat,
            'ngay_nhan' => $request->ngay_nhan,
        ]);

        $ma_PN = DB::table('phieunhap')
        ->latest('ma_PN')  
        ->first()
        ->ma_PN;

        // Lưu chi tiết nhập hàng
        foreach ($request->thuoc as $item) {
            DB::table('chitietnhaphang')->insert([
                'ma_PN' => $ma_PN, 
                'ma_thuoc' => $item['ma_thuoc'],
                'so_luong_nhap' => $item['so_luong'],
            ]);

            // Cập nhật số lượng tồn kho
            $thuoc = Thuoc::find($item['ma_thuoc']);
            if ($thuoc) {
                $thuoc->so_luong_ton += $item['so_luong']; 
                $thuoc->save();
            }
        }

        return redirect()->route('phieu-nhaps.index')->with('success', 'Phiếu nhập đã được thêm thành công.');
    }
    public function destroy(string $id)
    {
        $phieuNhap = PhieuNhap::where('ma_PN', $id)->firstOrFail();
        ChiTietNhapHang::where('ma_PN', $id)->delete(); 
        $phieuNhap->delete(); 
        return redirect()->route('phieu-nhaps.index')->with('success', 'Phiếu nhập đã được xóa thành công!');
    }
}
