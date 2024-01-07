<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanProducts extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class); 
    }

    public function return(){
        return $this->hasOne(ReturnProduct::class, 'laporan_product_id');
        }
}
