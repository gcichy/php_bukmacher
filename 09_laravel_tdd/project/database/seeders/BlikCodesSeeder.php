<?php

namespace Database\Seeders;

use App\Models\BlikCode;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlikCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $code = '';
            for ($j = 0; $j < 6; $j++) {
                $code .= $faker->numberBetween(0, 9);
            }
            BlikCode::create([
               'code' => $code
            ]);
        }
    }
}
