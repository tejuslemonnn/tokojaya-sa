<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function laporan(){
        return $this->belongsTo(Laporan::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}