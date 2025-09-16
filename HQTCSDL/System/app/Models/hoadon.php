<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hoadon extends Model
{
    use HasFactory;
    protected $table = 'HoaDon';
    protected $primaryKey = 'ma_HD'; 
    public $timestamps = false; 
    protected $fillable = ['ma_NV', 'ma_KH', 'ngay_tao']; 
    public function chiTietHD()
    {
        return $this->hasMany(ChiTietHD::class, 'ma_HD', 'ma_HD'); 
    }
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_KH', 'ma_KH');
    }
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'ma_NV', 'ma_NV');
    }
}
