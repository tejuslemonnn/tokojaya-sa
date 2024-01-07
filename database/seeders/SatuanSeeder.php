<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
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
            Satuan::create([
                'nama' => $value['nama'],
                'kode' => $value['kode'],
            ]);
        }
    }

    public function data()
    {
        return [
            ['nama' => 'pcs', 'kode' => 'pcs'],
            ['nama' => 'g', 'kode' => 'g'],
            ['nama' => 'Kg', 'kode' => 'kg'],
            ['nama' => 'l', 'kode' => 'l'],
            ['nama' => 'Ml', 'kode' => 'ml'],
        ];
    }
}
