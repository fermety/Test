<?php

namespace App\Pipelines;

use Closure;

class DebitingBalance
{
    /**
     * Handle an incoming request
     */
    public function handle(array $user_data, Closure $next): array
    {
        $session_duration = now()->diffInMinutes($user_data['user']->latestSession?->created_at);

        $user_data['user']->decrement('balance', $session_duration * config('settings.tariff'));

        return $next($user_data);
    }
}
