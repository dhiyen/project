<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Thuoc;
use App\Models\NhaCungCap;

class ThuocController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $thuocsQuery = Thuoc::with('nhacungcap');
        if ($query) {
            $thuocs = Thuoc::where('ten_thuoc', 'LIKE', "%{$query}%")
                ->orWhere('lieu_luong', 'LIKE', "%{$query}%")
                ->paginate(10);
        } else {
            $thuocs = Thuoc::paginate(10);
        }

        return view('thuocs.index', compact('thuocs', 'query'));
    }
    public function create()
    {
        $maxMaThuoc = DB::table('Thuoc')->max('ma_thuoc');
        $nextMaThuoc = $maxMaThuoc ? $maxMaThuoc + 1 : 1;
        $nhaCungCaps = NhaCungCap::all();
        return view('thuocs.create', compact('nhaCungCaps', 'nextMaThuoc'));
    }
    public function store(Request $request)
{
    // Validate dữ liệu nhập
    $request->validate([
        'ten_thuoc' => 'required',
        'thuong_hieu' => 'required',
        'lieu_luong' => 'required',
        'so_luong_ton' => 'required|integer',
        'gia_nhap' => 'required|numeric',
        'gia_ban' => 'required|numeric',
        'HSD' => 'required|date',
        'ma_NCC' => 'required|integer|exists:nhacungcap,ma_NCC',
    ]);

    // Kiểm tra số loại thuốc đã vượt quá 10 không
    $soLoaiThuoc = DB::table('NCC_Thuoc')
                     ->where('ma_NCC', $request->ma_NCC)
                     ->count(DB::raw('DISTINCT ma_thuoc'));

    if ($soLoaiThuoc >= 10) {
        return redirect()->back()->withErrors('Số loại thuốc đã vượt quá giới hạn cho phép.');
    }

    $result = '';

    // Gọi thủ tục ThemThuoc và lấy kết quả trả về
    $result = DB::select('
        DECLARE @result NVARCHAR(255);
        EXEC dbo.ThemThuoc 
            @ma_thuoc = ?, 
            @ten_thuoc = ?, 
            @thuong_hieu = ?, 
            @lieu_luong = ?, 
            @so_luong_ton = ?, 
            @gia_nhap = ?, 
            @gia_ban = ?, 
            @HSD = ?, 
            @ma_NCC = ?, 
            @result = @result OUTPUT;
        SELECT @result AS result;
    ', [$request->ma_thuoc, $request->ten_thuoc, $request->thuong_hieu, $request->lieu_luong, $request->so_luong_ton, $request->gia_nhap, $request->gia_ban, $request->HSD, $request->ma_NCC]);

    $message = $result[0]->result ?? 'Đã xảy ra lỗi khi thêm thuốc.';

    if (strpos($message, 'Error') !== false) {
        return redirect()->back()->withErrors($message)->withInput();
    }
    return redirect()->route('thuoc.index')->with('success', 'Thuốc đã được thêm thành công!');
}


    public function edit(string $id)
    {
        $thuoc = Thuoc::where('ma_thuoc', $id)->firstOrFail();
        return view('thuocs.edit', compact('thuoc'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'ten_thuoc' => 'required',
            'thuong_hieu' => 'required',
            'lieu_luong' => 'required',
            'so_luong_ton' => 'required|integer',
            'gia_nhap' => 'required|numeric',
            'gia_ban' => 'required|numeric',
            'HSD' => 'required|date',
        ]);

        $thuoc = Thuoc::where('ma_thuoc', $id)->firstOrFail();
        $thuoc->update($request->only(['ten_thuoc', 'thuong_hieu', 'lieu_luong', 'so_luong_ton', 'gia_nhap', 'gia_ban', 'HSD']));

        return redirect()->route('thuoc.index')->with('success', 'Thuốc đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        DB::table('NCC_Thuoc')->where('ma_thuoc', $id)->delete();
        $thuoc = Thuoc::where('ma_thuoc', $id)->firstOrFail();
        $thuoc->delete();
        return redirect()->route('thuoc.index')->with('success', 'Thuốc đã được xóa');
    }
}
