<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class TokenBucketService
{
    protected int $maxTokens = 100;

    public function consume(): bool
    {
        $key = 'api_tokens';

        if (! Redis::exists($key)) {

            Redis::setex(
                $key,
                60,
                $this->maxTokens
            );
        }

        $tokens = (int) Redis::get($key);

        if ($tokens <= 0) {
            return false;
        }

        Redis::decr($key);

        return true;
    }
}