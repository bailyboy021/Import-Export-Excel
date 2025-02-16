<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $countryList = [
            '62' => 'Indonesia',
            '970'  => 'Palestine',
            '90'  => 'Turkey',
            '39' => 'Italy',
            '81' => 'Japan',
        ];

        $customers = [];
        for ($i = 0; $i < 50; $i++) {
            // Pilih kode negara secara acak dari daftar
            $countryCode = $faker->randomElement(array_keys($countryList));
            $country = $countryList[$countryCode]; // Ambil nama negara berdasarkan kode negara
            $phoneNumber = $faker->numerify('##########'); // Menghasilkan 10 digit angka acak

            $customers[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $countryCode . '-' . $phoneNumber, // Format: kode negara - nomor
                'birth_place' => $faker->city,
                'birth_date' => $faker->date('Y-m-d', '2005-01-01'),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'country' => $country, // Sesuai dengan kode negara
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('customers')->insert($customers);
    }
}
