<?php

namespace Database\Seeders;

use App\Models\ParkingSpace;
use Illuminate\Database\Seeder;

class ParkingSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 7; $i++) {
            ParkingSpace::firstOrCreate([
                'name' => "a{$i}",
            ]);
        }
    }
}
