<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function detail_cashier()
    {
        return $this->hasMany(DetailCashier::class);
    }

    public function promoBundle()
    {
        return $this->hasOneThrough(PromoBundle::class, DetailCashier::class, 'cashier_id', 'id', 'id', 'promo_bundle_id');
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, DetailCashier::class, 'cashier_id', 'id', 'id', 'product_id');
    }
}
