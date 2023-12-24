<?php

namespace App\Pipelines;

use Closure;

class OpeningBarrier
{
    /**
     * Handle an incoming request
     */
    public function handle(array $user_data, Closure $next): array
    {
        return $next($user_data);
    }
}
