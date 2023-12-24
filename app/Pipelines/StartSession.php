<?php

namespace App\Pipelines;

use App\Models\ParkingSpace;
use Closure;

class StartSession
{
    /**
     * Handle an incoming request
     */
    public function handle(array $user_data, Closure $next): array
    {
        $user_data['parking_space'] = ParkingSpace::firstWhere('is_occupied', false);

        $user_data['session'] = $user_data['user']->sessions()->create([
            'parking_spaces_id' => $user_data['parking_space']->id,
            'is_parked' => true,
        ]);

        return $next($user_data);
    }
}
