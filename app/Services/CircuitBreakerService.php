<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class CircuitBreakerService
{
    protected int $threshold = 10;

    public function isOpen(): bool
    {
        return (
            Redis::get(
                'api_open_until'
            ) ?? 0
        ) > time();
    }

    public function recordFailure(): void
    {
        $count = Redis::incr(
            'api_failures'
        );

        if ($count >= $this->threshold) {

            Redis::set(
                'api_open_until',
                now()
                    ->addMinutes(2)
                    ->timestamp
            );

            Redis::del(
                'api_failures'
            );
        }
    }

    public function reset(): void
    {
        Redis::del(
            'api_failures'
        );

        Redis::del(
            'api_open_until'
        );
    }
}