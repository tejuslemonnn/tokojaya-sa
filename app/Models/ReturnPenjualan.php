<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPenjualan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id');
    }

    public function returnProducts() {
        return $this->hasMany(ReturnProduct::class);
    }
}
