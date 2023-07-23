<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Odds;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EventsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $e = Event::create([
                'opponent_1' => $faker->randomElement(['Manchester City', 'Manchester United', 'Newcastle United','Nottingham Forest','Southampton', 'Tottenham Hotspur']),
                'opponent_2' => $faker->randomElement(['Manchester City', 'Manchester United', 'Newcastle United','Nottingham Forest','Southampton', 'Tottenham Hotspur']),
                'date' => date_format($faker->dateTimeBetween('-01 weeks', '+01 weeks'), 'Y-m-d'),
                'discipline' => $faker->randomElement(['Piłka Nożna', 'Siatkówka', 'Piłka Ręczna', 'Dart', 'Koszykówka', 'E-sport']),
                'time' => $faker->time('H:i'),
                'timezone' => $faker->timezone,
                'league' => $faker->randomElement(['LaLiga', 'Ekstraklasa', 'Bundesliga', 'Piotrkowska Klasa Okręgowa', 'BPL', 'Lique 1']),
                'round' => $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 'Semi-Final', 'Final', '1/8 Final']),
                'status' => $faker->randomElement(['upcoming', 'ended', 'live']),
                'score' => $faker->numberBetween(0, 10) . ':' . $faker->numberBetween(0, 10)
            ]);
            $win = $faker->randomFloat(2, 0.02, 0.98);
            $win2 = $faker->randomFloat(2, 0.02, 1-$win-0.02);
            $win = floor((1/$win)*100)/100;
            $win2 = floor((1/$win2)*100)/100;
            $sum = $win + $win2;
            Odds::create([
                'is_special' => false,
                'event_id' => $e->id,
                'win_op_1' => $win,
                'win_op_2' => $win2,
                'sum' => $sum
            ]);
        }
    }
}
