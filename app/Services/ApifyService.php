<?php

namespace App\Services;

use App\Services\Contracts\ProfileProviderInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class ApifyService implements ProfileProviderInterface
{
    public function fetch(string $username): array
    {
        $token = config('services.apify.token');

        if (!$token) {
            throw new Exception(
                'APIFY_TOKEN missing'
            );
        }

        $response = Http::connectTimeout(5)
            ->timeout(30)
            ->retry(
                3,
                2000,
                function ($exception, $request) {
                    return true;
                }
            )
            ->withToken($token)
            ->post(
                'https://api.apify.com/v2/acts/apify~instagram-scraper/run-sync-get-dataset-items',
                [
                    'directUrls' => [
                        "https://www.instagram.com/{$username}/"
                    ]
                ]
            );

        \Log::info('response' , [
            'response' => $response
        ]);

        if ($response->status() === 404) {
            throw new Exception(
                'Profile not found'
            );
        }

        if ($response->status() === 401) {
            throw new Exception(
                'Invalid API credentials'
            );
        }

        if (
            $response->status() === 429 ||
            $response->serverError()
        ) {
            throw new Exception(
                'Retryable API error'
            );
        }

        if (! $response->successful()) {
            throw new Exception(
                'Apify request failed'
            );
        }

        $data = $response->json();

        if (empty($data)) {
            throw new Exception(
                'No profile data returned'
            );
        }

        $profile = $data[0];

        return [
            'followers_count' =>
                $profile['followersCount'] ?? 0,

            'following_count' =>
                $profile['followsCount'] ?? 0,

            'posts_count' =>
                $profile['postsCount'] ?? 0,

            'bio' =>
                $profile['biography'] ?? null,

            'profile_picture' =>
                $profile['profilePicUrl'] ?? null,
        ];
    }
}