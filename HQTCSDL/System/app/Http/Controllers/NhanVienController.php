<?php

namespace App\Http\Controllers;

use App\Models\NhanVien; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class NhanVienController
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $nhanViensQuery = NhanVien::query();  
        if ($query) {
            $nhanViensQuery->where('ten_NV', 'LIKE', "%{$query}%")
                ->orWhere('SDT', 'LIKE', "%{$query}%");
        }    
        
        $nhanViens = $nhanViensQuery->paginate(10);
        return view('nhanviens.index', compact('nhanViens','query'));
    }

    public function create()
    {
        $maxMaNV = DB::table('nhanvien')->max('ma_NV');
        $nextMaNV = $maxMaNV ? $maxMaNV + 1 : 1;
        return view('nhanviens.create',compact('nextMaNV'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_NV' => 'required',
            'SDT' => 'required',
            'dia_chi' => 'required',
            'ngay_sinh' => 'required|date',
        ]);
    
        $ten_NV = $request->ten_NV;
        $ngay_sinh = $request->ngay_sinh;
        $age = \Carbon\Carbon::parse($ngay_sinh)->age;
    
        if ($age < 18) {
            return redirect()->back()->withErrors("Nhân viên $ten_NV chưa đủ 18 tuổi.");
        }
        
        $maxMaNV = DB::table('nhanvien')->max('ma_NV');
        $nextMaNV = $maxMaNV ? $maxMaNV + 1 : 1;
    
        try {
            DB::statement('EXEC ThemNhanVien @ma_NV = ?, @ten_NV = ?, @SDT = ?, @dia_chi = ?, @ngay_sinh = ?', [
                $nextMaNV, 
                $ten_NV,
                $request->SDT,
                $request->dia_chi,
                $ngay_sinh,
            ]);
    
            return redirect()->route('nhan-viens.index')->with('success', 'Nhân viên đã được thêm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm nhân viên: ' . $e->getMessage());
        }
    }
    
    public function edit(string $id)
    {
        $nhanvien = NhanVien::findOrFail($id); 
        return view('nhanviens.edit', compact('nhanvien'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'ten_NV' => 'required',
            'SDT' => 'required',
            'dia_chi' => 'required',
            'ngay_sinh' => 'required|date',
        ]);
        
        $nhanvien = NhanVien::where('ma_NV', $id)->firstOrFail();
        $nhanvien->update($request->all());
        return redirect()->route('nhan-viens.index')->with('success', 'Nhân viên đã được cập nhật thành công!');
    }

    public function destroy(string $id)
    {
        $nhanvien = NhanVien::where('ma_NV', $id)->firstOrFail();
        $nhanvien->delete(); 
        return redirect()->route('nhan-viens.index')->with('success', 'Nhân viên đã được xóa thành công!');
    }
}
