<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $accountSeeder = new AccountSeeder();
        $accountSeeder->run();
    }
}
