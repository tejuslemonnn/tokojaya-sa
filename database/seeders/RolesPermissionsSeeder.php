<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $dataPermissions = $this->dataPermissions();
        foreach ($dataPermissions as $value) {
            Permission::create([
                'name' => $value['name']
            ]);
        }
        
        Role::create(['name' => 'owner'])->givePermissionTo('manage account');
        Role::create(['name' => 'kepala-kasir'])->givePermissionTo('manage shop');
        Role::create(['name' => 'kasir'])->givePermissionTo('manage sale');
    }

    public function dataPermissions()
    {
        return [
            ['name' => 'manage sale'],
            ['name' => 'manage shop'],
            ['name' => 'manage account'],
        ];
    }
}
