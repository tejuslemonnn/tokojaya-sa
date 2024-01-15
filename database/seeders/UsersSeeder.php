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
            'name'          => "Pak Jaya",
            'username'          => "owner",
            'password'          => Hash::make('demo'),
        ]);

        $this->addDummyInfo($faker, $owner);

        $owner->assignRole('owner');

        $kasir = User::create([
            'name'          => $faker->name,
            'username'          => "kasir",
            'password'          => Hash::make('demo'),
        ]);

        $this->addDummyInfo($faker, $kasir);
        $kasir->assignRole('kasir');

        for ($i = 1; $i < 6; $i++) {
            $kasir = User::create([
                'name'          => $faker->name,
                'username'          => "kasir-" . $i,
                'password'          => Hash::make('demo'),
            ]);

            $this->addDummyInfo($faker, $kasir);
            $kasir->assignRole('kasir');
        }

        $kepalaKasir = User::create([
            'name'          => $faker->name,
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
            'shift' => rand(1, 2),
        ];

        $info = new UserInfo();
        foreach ($dummyInfo as $key => $value) {
            $info->$key = $value;
        }
        $info->user()->associate($user);
        $info->save();
    }
}
