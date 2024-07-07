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
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function promoBundle()
    {
        return $this->hasOne(PromoBundle::class, 'id', 'promo_bundle_id');
    }

    public function return()
    {
        return $this->hasOne(ReturnProduct::class, 'laporan_product_id');
    }
}
