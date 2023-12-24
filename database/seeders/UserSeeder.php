<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (array_chunk(User::factory(50000)->raw(), 1000) as $chunk) {
            User::insert($chunk);
        }
    }
}
