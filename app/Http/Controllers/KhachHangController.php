<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhachHang; 
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
class KhachHangController
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $khachHangsQuery = KhachHang::query();  
        if ($query) {
            $khachHangsQuery->where('ten_KH', 'LIKE', "%{$query}%")
                ->orWhere('SDT_KH', 'LIKE', "%{$query}%");
        }    
        
        $khachHangs = $khachHangsQuery->paginate(10); 
        return view('khachhangs.index', compact('khachHangs','query'));
    }

    public function show(Request $request)
    {
        $thang = $request->input('thang', null);  
        $nam = $request->input('nam', null);  
        
        $results = DB::select('
            SELECT * 
            FROM dbo.fn_KhachHangCoHDTheoThangNam(?, ?)', [
            $thang, 
            $nam
        ]);
        
        if ($results) {
            // Chuyển đổi mảng kết quả thành một Collection (để sử dụng phân trang)
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10; 
            $currentResults = collect($results);
            
            $paginatedResults = new LengthAwarePaginator(
                $currentResults->forPage($currentPage, $perPage), 
                $currentResults->count(), 
                $perPage, 
                $currentPage, 
                ['path' => $request->url(), 'query' => $request->query()] 
            );
        } else {
            $paginatedResults = collect(); 
        }
        return view('khachhangs.show', compact('paginatedResults', 'thang', 'nam'));
    }    
    public function create()
    {
        return view('khachhangs.create');
    }
    public function store(Request $request)
{
    $request->validate([
        'ten_KH' => 'required',
        'SDT_KH' => 'required',
        'gioi_tinh' => 'required',
        'ngay_sinh' => 'required|date',
        'diem_tich' => 'integer'
    ]);

    try {
        $existingCustomer = KhachHang::where('SDT_KH', $request->SDT_KH)->first();
        if ($existingCustomer) {
            return redirect()->back()->withErrors(['error' => 'Khách hàng đã có trong hệ thống!'])->withInput();
        }
        KhachHang::create($request->all());

        return redirect()->route('khach-hangs.index')->with('success', 'Khách hàng đã được thêm thành công!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
    }
}


    public function edit($id)
    {
        $khachhang = KhachHang::where('ma_KH', $id)->firstOrFail();
        return view('khachhangs.edit', compact('khachhang'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_KH' => 'required',
            'SDT_KH' => 'required',
            'gioi_tinh' => 'required',
            'ngay_sinh' => 'required|date',
            'diem_tich' => 'integer'
        ]);

        $khachhang = KhachHang::where('ma_KH', $id)->firstOrFail();
        $khachhang->update($request->only(['ten_KH', 'SDT_KH', 'gioi_tinh', 'ngay_sinh', 'diem_tich']));

        return redirect()->route('khach-hangs.index')->with('success', 'Khách hàng đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $khachhang = KhachHang::where('ma_KH', $id)->firstOrFail();
        $khachhang->delete();
        return redirect()->route('khach-hangs.index')->with('success', 'Khách hàng đã được xóa thành công!');
    }
}
