<?php

namespace App\Pipelines;

use App\Models\ParkingSpace;
use Closure;

class FinishSession
{
    /**
     * Handle an incoming request
     */
    public function handle(array $user_data, Closure $next): array
    {
        $user_data['parking_space'] = ParkingSpace::find($user_data['user']->load('latestSession')->latestSession->parking_spaces_id);

        $user_data['session'] = $user_data['user']->sessions()->create([
            'parking_spaces_id' => $user_data['parking_space']->id,
            'is_parked' => false,
        ]);

        return $next($user_data);
    }
}
