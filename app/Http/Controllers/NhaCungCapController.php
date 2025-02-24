<?php

namespace App\Http\Controllers;

use App\Models\NhaCungCap; 
use App\Models\Thuoc; 
use App\Models\NCC_Thuoc;
use App\Models\nhanvien;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class NhaCungCapController extends Controller
{
    public function index(Request $request)
    {
        $trungBinhSoLoaiThuoc = DB::select('SELECT dbo.fn_TrungBinhSoLoaiThuoc() AS trung_binh')[0]->trung_binh;
        
        $query = $request->input('query');
        $nhaCungCapsQuery = NhaCungCap::query();  
        if ($query) {
            $nhaCungCapsQuery->where('ten_NCC', 'LIKE', "%{$query}%")
                ->orWhere('SDT', 'LIKE', "%{$query}%");
        }    
        
        $nhaCungCaps = $nhaCungCapsQuery->paginate(10);
        return view('nha_cung_caps.index', compact('nhaCungCaps','trungBinhSoLoaiThuoc','query'));
    }

    public function showThuocs($maNCC)
    {
        $thuocs = DB::table('NCC_Thuoc')
            ->join('Thuoc', 'NCC_Thuoc.ma_thuoc', '=', 'Thuoc.ma_thuoc')
            ->where('NCC_Thuoc.ma_NCC', $maNCC)
            ->select('Thuoc.ma_thuoc', 'Thuoc.ten_thuoc', 'Thuoc.gia_ban', 'Thuoc.so_luong_ton')
            ->get();
        
        $nhaCungCap = DB::table('v_SLThuocNCC')
            ->where('ma_NCC', $maNCC)
            ->first();

        $soLoaiThuoc = $nhaCungCap->so_loai_thuoc;
        $tongGiaBan = $nhaCungCap->tong_gia_ban;

        return view('nha_cung_caps.thuocs', compact('thuocs', 'nhaCungCap', 'soLoaiThuoc', 'tongGiaBan'));
    }
    public function create()
    {
        $maxMaNCC = DB::table('nhacungcap')->max('ma_NCC');
        $nextMaNCC = $maxMaNCC ? $maxMaNCC + 1 : 1;
        return view('nha_cung_caps.create',compact('nextMaNCC'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_NCC' => 'required',
            'SDT' => 'required',
            'dia_chi' => 'required'
        ]);
        NhaCungCap::create($request->all());
        return redirect()->route('nha-cung-caps.index')->with('success', 'Nhà cung cấp đã được thêm thành công!');
    }
     public function edit($id)
    {
        $nhaCungCap = NhaCungCap::where('ma_NCC', $id)->firstOrFail();
        return view('nha_cung_caps.edit', compact('nhaCungCap'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_NCC' => 'required',
            'SDT' => 'required',
            'dia_chi' => 'required'
        ]);

        $nhaCungCap = NhaCungCap::where('ma_NCC', $id)->firstOrFail(); 
        $nhaCungCap->update($request->only(['ten_NCC', 'SDT', 'dia_chi']));

        return redirect()->route('nha-cung-caps.index')->with('success', 'Nhà cung cấp đã được cập nhật thành công!');
    }
    public function destroy($id)
    {
        $nhaCungCap = NhaCungCap::findOrFail($id);
        $nhaCungCap->delete();
        return redirect()->route('nha-cung-caps.index')->with('success', 'Nhà cung cấp đã được xóa thành công!');
    }
}
