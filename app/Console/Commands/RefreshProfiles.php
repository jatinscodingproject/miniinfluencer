<?php

namespace App\Console\Commands;

use App\Jobs\FetchProfileJob;
use App\Models\Profile;
use Illuminate\Console\Command;

class RefreshProfiles extends Command
{
    protected $signature = 'profiles:refresh';

    protected $description =
        'Refresh stale profiles';

    public function handle(): int
    {
        Profile::query()
            ->where(function ($query) {

                $query->whereNull(
                    'last_refreshed_at'
                )
                ->orWhere(
                    'last_refreshed_at',
                    '<',
                    now()->subHour()
                );

            })
            ->each(function ($profile) {

                FetchProfileJob::dispatch(
                    $profile->id
                );

            });

        $this->info(
            'Profiles queued successfully.'
        );

        return self::SUCCESS;
    }
}