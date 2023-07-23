<?php

namespace Database\Seeders;

use App\Models\Odds;
use App\Models\SpecialEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SpecialEventsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = ['Czy w przyszłym roku odbędzie się konferencja dotycząca nowych technologii w branży IT?',
            'Czy w przyszłym roku zostanie wprowadzony nowy model samochodu marki Tesla?',
            'Czy w przyszłym roku zostanie ogłoszony nowy konkurs literacki dla młodych pisarzy?',
            'Czy w przyszłym roku zostanie ogłoszony nowy budżet Unii Europejskiej?',
            'Czy w przyszłym roku odbędzie się nowa edycja festiwalu filmowego w Cannes?'
        ];
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            $se = SpecialEvent::create([
                'question' => $faker->randomElement($questions),
                'answer_1' => 'Tak',
                'answer_2' => 'Nie',
                'correct' => $faker->randomElement(['Tak', 'Nie'])
            ]);
            $win = $faker->randomFloat(2, 0.01, 0.98);
            $win2 = $faker->randomFloat(2, 0.01, 1-$win-0.02);
            $win = floor((1/$win)*100)/100;
            $win2 = floor((1/$win2)*100)/100;
            $sum = $win + $win2;
            Odds::create([
                'is_special' => true,
                'event_id' => $se->id,
                'win_op_1' => $win,
                'win_op_2' => $win2,
                'sum' => $sum
            ]);
        }
    }
}
