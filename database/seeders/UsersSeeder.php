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
            'first_name'        => "Owner",
            'last_name'         => "Shop",
            'email'             => 'owner@demo.com',
            'password'          => Hash::make('demo'),
            'email_verified_at' => now(),
        ]);

        $this->addDummyInfo($faker, $owner);

        $owner->assignRole('owner');

        $kasir = User::create([
            'first_name'        => "Kasir",
            'last_name'         => "",
            'email'             => 'kasir@demo.com',
            'password'          => Hash::make('demo'),
            'email_verified_at' => now(),
        ]);

        $this->addDummyInfo($faker, $kasir);

        $kasir->assignRole('kasir');

        $kepalaKasir = User::create([
            'first_name'        => "Kepala",
            'last_name'         => "Kasir",
            'email'             => 'kepala-kasir@demo.com',
            'password'          => Hash::make('demo'),
            'email_verified_at' => now(),
        ]);

        $this->addDummyInfo($faker, $kepalaKasir);

        $kepalaKasir->assignRole('kepala-kasir');
    }

    private function addDummyInfo(Generator $faker, User $user)
    {
        $dummyInfo = [
            'company'  => $faker->company,
            'phone'    => $faker->phoneNumber,
            'website'  => $faker->url,
            'language' => $faker->languageCode,
            'country'  => $faker->countryCode,
        ];

        $info = new UserInfo();
        foreach ($dummyInfo as $key => $value) {
            $info->$key = $value;
        }
        $info->user()->associate($user);
        $info->save();
    }
}
