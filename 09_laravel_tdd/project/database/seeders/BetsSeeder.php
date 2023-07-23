<?php

namespace Database\Seeders;

use App\Models\Bet;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BetsSeeder extends Seeder
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
            $win_price = 0;
            $stake = $faker->randomFloat('2', 0, 1000);
            $total_odd = $faker->randomFloat('2', 0, 100);
            $status = $faker->numberBetween(0, 1);
            if ($status) {
                $bet_result = $faker->randomElement([0,2]);
                if ($bet_result == 2) {
                    $win_price = $stake*$total_odd*0.85;
                }
            } else {
                $bet_result = 0;
                $win_price = 0;
            }

            Bet::create([
                'user_id' => $faker->numberBetween(1, 10),
                'date' => date_format($faker->dateTimeBetween('-01 weeks', '+01 weeks'), 'Y-m-d'),
                "stake" => $stake,
                "total_odd" => $total_odd,
                "status" => $status, //true - skończony, false - trwa
                "bet_result" => $bet_result, //0 - przegrany, 1 w trakcie, 2 - wygrany
                'win_price' => $win_price,
            ]);
        }
    }
}
