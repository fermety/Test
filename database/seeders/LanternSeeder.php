<?php

namespace Database\Seeders;

use App\Models\Lantern;
use App\Models\ParkingSpace;
use Illuminate\Database\Seeder;

class LanternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParkingSpace::get('id')
            ->each(fn (ParkingSpace $parking_space) => Lantern::firstOrCreate([
                'parking_spaces_id' => $parking_space->id,
            ]));
    }
}
