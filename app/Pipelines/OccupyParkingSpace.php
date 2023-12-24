<?php

namespace App\Pipelines;

use Closure;

class OccupyParkingSpace
{
    /**
     * Handle an incoming request
     */
    public function handle(array $user_data, Closure $next): array
    {
        $user_data['parking_space']->update([
            'is_occupied' => true,
        ]);

        return $next($user_data);
    }
}
