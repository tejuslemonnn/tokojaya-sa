<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoBundleItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    function promoBundle()
    {
        return $this->hasOne(PromoBundle::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
