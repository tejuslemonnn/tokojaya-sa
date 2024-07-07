<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoBundle extends Model
{
    use HasFactory;

    protected $guarded = [];

    function promoBundleItems()
    {
        return $this->hasMany(PromoBundleItem::class, 'promo_bundle_id', 'id');
    }
}
