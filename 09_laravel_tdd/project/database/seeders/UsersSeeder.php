<?php

namespace Database\Seeders;

use App\Models\Premium;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $faker = Faker::create();
            $name = $faker->firstName;
            $phoneNumber = '+48';
            for ($j = 0; $j < 9; $j++) {
                $phoneNumber .= rand(0, 9);
            }
            $pesel = "010708";
            for ($j = 0; $j < 5; $j++) {
                $pesel .= rand(0, 9);
            }
            $id = User::create([
                'name' => $name,
                'surname' => $faker->lastName,
                'nickname' =>  $name.$i,
                'email' => $faker->email,
                'password' => password_hash('123', PASSWORD_DEFAULT),//bcrypt('123'),
                'deposit' => 100 + $i,
                'premium' => $i % 2 == 0,
                'confirmed' => true,
                'phone_number' => $phoneNumber,
                'person_number' => $pesel
            ]);
            if ($i % 2 == 0) {
                $si = '';
                for ($j = 0; $j<9; $j++) {
                    $si .= $faker->numberBetween(1, 4);
                }
                Premium::create([
                    'scratches_left' => $faker->numberBetween(0, 3),
                    'scratchcard_id' => $si,
                    'user_id' => $id->id,
                    'expiration_date' => date_format($faker->dateTimeBetween('now', '+01 weeks'), 'Y-m-d'),
                    'harakiried' => $faker->boolean,
                ]);
            }
        }
    }
}
