<?php

namespace App\Pipelines;

use Closure;

class ReleaseParkingSpace
{
    /**
     * Handle an incoming request
     */
    public function handle(array $user_data, Closure $next): array
    {
        $user_data['parking_space']->update([
            'is_occupied' => false,
        ]);

        return $next($user_data);
    }
}
