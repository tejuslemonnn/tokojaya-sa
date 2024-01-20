<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function laporan_products()
    {
        return $this->hasMany(LaporanProducts::class);
    }

    public function laporan_return_products()
    {
        return $this->hasMany(LaporanProductReturns::class);
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, LaporanProducts::class, 'laporan_id', 'id', 'id', 'product_id');
    }

    public static function calculateTotal($date = null)
    {
        $query = self::query();

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        return $query->sum('total');
    }
}
