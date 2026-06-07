<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class WebhookController extends Controller
{
    public function handle(
        Request $request
    )
    {
        $payload =
            $request->getContent();

        $signature =
            $request->header(
                'X-Webhook-Signature'
            );

        $expected = hash_hmac(
            'sha256',
            $payload,
            config(
                'services.webhook.secret'
            )
        );

        if (
            ! hash_equals(
                $expected,
                $signature
            )
        ) {

            return response()->json(
                [
                    'message' =>
                        'Invalid signature'
                ],
                401
            );
        }

        $nonce =
            $request->header(
                'X-Webhook-Id'
            );

        if (
            Redis::exists(
                "webhook:{$nonce}"
            )
        ) {

            return response()->json(
                [
                    'message' =>
                        'Replay detected'
                ],
                409
            );
        }

        Redis::setex(
            "webhook:{$nonce}",
            86400,
            true
        );

        $data = $request->validate([
            'username' =>
                ['required'],
            'followers_count' =>
                ['required'],
            'following_count' =>
                ['required'],
            'posts_count' =>
                ['required'],
        ]);

        $profile = Profile::where(
            'username',
            $data['username']
        )->first();

        if (! $profile) {

            return response()->json(
                [
                    'message' =>
                        'Profile not found'
                ],
                404
            );
        }

        DB::transaction(function () use (
            $profile,
            $data
        ) {

            ProfileSnapshot::create([
                'profile_id' =>
                    $profile->id,

                'followers_count' =>
                    $data['followers_count'],

                'following_count' =>
                    $data['following_count'],

                'posts_count' =>
                    $data['posts_count'],

                'snapshot_at' =>
                    now(),
            ]);

            $profile->update([
                'followers_count' =>
                    $data['followers_count'],

                'following_count' =>
                    $data['following_count'],

                'posts_count' =>
                    $data['posts_count'],

                'last_refreshed_at' =>
                    now(),

                'status' =>
                    'fetched',
            ]);
        });

        return response()->json([
            'status' => 'success'
        ]);
    }
}