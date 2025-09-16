<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class LichSuMuaController extends Controller
{
    public function index(Request $request)
{
    $query = $request->input('ten_khach_hang', '%');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    if ($startDate || $endDate) {
        // Truy vấn gọi stored procedure sp_LichSuMuaKH với các tham số truyền vào
        $results = DB::select('EXEC sp_LichSuMuaKH @TenKhachHang = ?, @StartDate = ?, @EndDate = ?', [
            "%$query%",   
            $startDate ? $startDate : null, 
            $endDate ? $endDate : null,     
        ]);

        $paginatedResults = new LengthAwarePaginator(
            $results, 
            count($results),  
            10,                // Items per page
            $request->input('page', 1), 
            ['path' => $request->url(), 'query' => $request->query()] 
        );
    } else {
        $paginatedResults = DB::table('v_LichSuMuaKH as v')
            ->where('v.ten_KH', 'LIKE', "%{$query}%")  
            ->paginate(10);
    }
    $avg = DB::table('v_LichSuMuaKH')
        ->select(
            DB::raw('AVG(So_don_hang) AS trung_binh_so_don_hang'),
            DB::raw('AVG(Tong_chi_tieu) AS trung_binh_tong_chi_tieu')
        )
        ->first();
    return view('lichsumua.index', compact('paginatedResults', 'query', 'startDate', 'endDate', 'avg'));
}
}
