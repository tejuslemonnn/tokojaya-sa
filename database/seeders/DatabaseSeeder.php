<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\SatuanSeeder;
use Database\Seeders\CategoriesSeeder;
use Database\Seeders\RolesPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesPermissionsSeeder::class,
            UsersSeeder::class,
            CategoriesSeeder::class,
            SatuanSeeder::class
        ]);

        Product::create([
            'kategori_id' => 1,
            'satuan_id' => 1,
            'kode' => 1100,
            'nama_produk' => "Sabun Nuvo",
            "harga" => 4000,
            "stok" => 200
        ]);

        Product::create([
            'kategori_id' => 2,
            'satuan_id' => 1,
            'kode' => 1200,
            'nama_produk' => "Indomie Mie Soto",
            "harga" => 4000,
            "stok" => 150,
            "diskon" => 10
        ]);

        Product::create([
            'kategori_id' => 3,
            'satuan_id' => 3,
            'kode' => 1300,
            'nama_produk' => "Beras A",
            "harga" => 20000,
            "stok" => 500,
            "diskon" => 5
        ]);

        Product::create([
            'kategori_id' => 4,
            'satuan_id' => 4,
            'kode' => 1400,
            'nama_produk' => "Minyak Goreng Mama Suka",
            "harga" => 30000,
            "stok" => 500,
            "diskon" => 0
        ]);
    }
}
