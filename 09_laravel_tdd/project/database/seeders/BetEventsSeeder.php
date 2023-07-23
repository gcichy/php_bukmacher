<?php

namespace Database\Seeders;

use App\Models\Bet;
use App\Models\BetEvent;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BetEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            BetEvent::create([
                'bet_id' => $faker->numberBetween(1, 10),
                'event_id' => $faker->numberBetween(1, 10),
                'answer' => $faker->randomElement(["Tak", "Nie", "Real Madryt", "Widzew Łódź", "Polska", "Niemcy", "Portugalia"])
            ]);
        }
    }
}
