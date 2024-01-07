<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->data();

        foreach ($data as $value) {
            Category::create([
                'nama' => $value['nama'],
                'kode' => $value['kode'],
            ]);
        }
    }

    public function data()
    {
        return [
            ['nama' => 'Sabun', 'kode' => 'sabun'],
            ['nama' => 'Mie Instant', 'kode' => 'mie-instant'],
            ['nama' => 'Beras', 'kode' => 'beras'],
            ['nama' => 'Minyak Goreng', 'kode' => 'minyak-goreng'],
        ];
    }
}
