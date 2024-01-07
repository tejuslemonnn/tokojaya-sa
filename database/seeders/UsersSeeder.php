<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInfo;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $owner = User::create([
            'username'          => "owner",
            'password'          => Hash::make('demo'),
        ]);

        $this->addDummyInfo($faker, $owner);

        $owner->assignRole('owner');

        $kasir = User::create([
            'username'          => "kasir",
            'password'          => Hash::make('demo'),
        ]);

        $this->addDummyInfo($faker, $kasir);

        $kasir->assignRole('kasir');

        $kepalaKasir = User::create([
            'username'          => "kepala-kasir",
            'password'          => Hash::make('demo'),
        ]);

        $this->addDummyInfo($faker, $kepalaKasir);

        $kepalaKasir->assignRole('kepala-kasir');
    }

    private function addDummyInfo(Generator $faker, User $user)
    {
        $dummyInfo = [
            'phone'    => $faker->phoneNumber,
            'address'    => $faker->address,
        ];

        $info = new UserInfo();
        foreach ($dummyInfo as $key => $value) {
            $info->$key = $value;
        }
        $info->user()->associate($user);
        $info->save();
    }
}
