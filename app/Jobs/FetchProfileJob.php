<?php

namespace App\Jobs;

use App\Models\Profile;
use App\Models\ProfileSnapshot;
use App\Services\Contracts\ProfileProviderInterface;
use App\Services\CircuitBreakerService;
use App\Services\TokenBucketService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Throwable;

class FetchProfileJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public string $profileId
    ) {}

    public function handle(
        ProfileProviderInterface $provider,
        CircuitBreakerService $breaker,
        TokenBucketService $bucket
    ): void
    {
        $profile = Profile::findOrFail(
            $this->profileId
        );

        $lock = DB::selectOne(
            'SELECT pg_try_advisory_lock(?) AS locked',
            [crc32($profile->id)]
        );

        if (!$lock->locked) {
            return;
        }

        try {

            if ($breaker->isOpen()) {

                $this->release(120);

                return;
            }

            if (! $bucket->consume()) {

                $this->release(60);

                return;
            }

            $profile->update([
                'status' => 'fetching',
            ]);

            $data = $provider->fetch(
                $profile->username
            );

            DB::transaction(function () use (
                $profile,
                $data
            ) {

                ProfileSnapshot::create([
                    'profile_id' => $profile->id,
                    'followers_count' =>
                        $data['followers_count'],
                    'following_count' =>
                        $data['following_count'],
                    'posts_count' =>
                        $data['posts_count'],
                    'snapshot_at' => now(),
                ]);

                $profile->update([
                    'followers_count' =>
                        $data['followers_count'],

                    'following_count' =>
                        $data['following_count'],

                    'posts_count' =>
                        $data['posts_count'],

                    'bio' =>
                        $data['bio'],

                    'profile_picture' =>
                        $data['profile_picture'],

                    'last_refreshed_at' =>
                        now(),

                    'status' =>
                        'fetched',
                ]);
            });

            $breaker->reset();

        } catch (Throwable $e) {

            $breaker->recordFailure();

            $profile->update([
                'status' => 'failed',
                'error_message' =>
                    $e->getMessage(),
            ]);

            throw $e;

        } finally {

            DB::statement(
                'SELECT pg_advisory_unlock(?)',
                [crc32($profile->id)]
            );
        }
    }
}