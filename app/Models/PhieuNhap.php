<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhieuNhap extends Model
{
    use HasFactory;
    protected $table = 'phieunhap';
    protected $primaryKey = 'ma_PN'; 
    public $incrementing = true; 
    public $timestamps = false;
    
    protected $fillable = ['ngay_dat','ngay_nhan'];
    public function chitiet()
    {
        return $this->hasMany(ChiTietNhapHang::class, 'ma_PN', 'ma_PN');
    }
}
