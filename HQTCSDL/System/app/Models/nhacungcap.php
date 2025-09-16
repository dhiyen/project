<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nhacungcap extends Model
{
    use HasFactory;
    protected $table = 'nhacungcap';
    protected $primaryKey = 'ma_NCC'; 
    public $incrementing = false; 
    public $timestamps = false;
    
    protected $fillable = ['ma_NCC','ten_NCC','SDT','dia_chi'];
    public function thuoc()
    {
        return $this->belongsToMany(Thuoc::class, 'ncc_thuoc', 'ma_ncc', 'ma_thuoc');
    }
}
