<?php

namespace App\Pipelines;

use App\Models\Lantern;
use Closure;

class UpdateLanterns
{
    /**
     * Handle an incoming request
     */
    public function handle(array $user_data, Closure $next): array
    {
        Lantern::where('parking_spaces_id', $user_data['parking_space']->id)->update(['toggle' => true]);

        return $next($user_data);
    }
}
