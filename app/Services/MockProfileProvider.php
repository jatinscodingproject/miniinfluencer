<?php

namespace App\Services;

use App\Services\Contracts\ProfileProviderInterface;

class MockProfileProvider implements ProfileProviderInterface
{
    public function fetch(string $username): array
    {
        return [
            'followers_count' => rand(10000, 500000),
            'following_count' => rand(100, 5000),
            'posts_count' => rand(100, 3000),
            'bio' => "Bio for {$username}",
            'profile_picture' => null,
        ];
    }
}