<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ncc_thuoc extends Model
{
    use HasFactory;
    protected $fillable = ['ma_NCC', 'ma_thuoc'];
    public function nhacungcap()
    {
        return $this->belongsTo(nhacungcap::class, 'ma_NCC');
    }
    public function thuoc()
    {
        return $this->belongsTo(thuoc::class, 'ma_thuoc');
    }
}
