<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        $this->call(SpecialEventsSeeder::class);
        $this->call(EventsSeeder::class);
        $this->call(BlikCodesSeeder::class);
        $this->call(BetEventsSeeder::class);
        $this->call(BetsSeeder::class);
    }
}
