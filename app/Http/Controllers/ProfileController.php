<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Profile;
use App\Jobs\FetchProfileJob;

class ProfileController extends Controller
{

    public function index(Request $request)
    {   
        try{
            $profiles = Profile::query()
            ->when(
                $request->filled('q'),
                fn ($q) =>
                $q->where(
                    'username',
                    'ilike',
                    "%{$request->q}%"
                )
            )
            ->when(
                $request->filled('status'),
                fn ($q) =>
                $q->where(
                    'status',
                    $request->status
                )
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

            return Inertia::render(
                'profiles/Index',
                [
                    'profiles' => $profiles,
                    'filters' => [
                        'q' => $request->q,
                        'status' => $request->status,
                    ],
                ]
            );
        } catch (\Throwable $e) {
            \Log::error(
                'Failed to load profile details',
                [
                    'profile_id' => $profile->id ?? null,
                    'message' => $e->getMessage(),
                ]
            );
            abort(
                500,
                'Failed to load profile details.'
            );
        }
       
    }

    public function store(Request $request)
    {   
        try{
            $validated =
            $request->validate([
                'username' =>
                    ['required']
            ]);

            $username = strtolower(
                trim(
                    str_replace(
                        '@',
                        '',
                        $validated['username']
                    )
                )
            );

            $profile = Profile::create([
                'username' => $username,
                'status' => 'pending',
            ]);

            FetchProfileJob::dispatch(
                $profile->id
            );

            return redirect()
                ->route(
                    'profiles.show',
                    $profile
                );
        } catch (\Throwable $e) {
            \Log::error(
                'Failed to load profile details',
                [
                    'profile_id' => $profile->id ?? null,
                    'message' => $e->getMessage(),
                ]
            );
            abort(
                500,
                'Failed to load profile details.'
            );
        }
      
    }

    public function create()
    {   
        try{
            return Inertia::render(
                'profiles/Create'
            );
        } catch (\Throwable $e) {
            \Log::error(
                'Failed to load profile details',
                [
                    'profile_id' => $profile->id ?? null,
                    'message' => $e->getMessage(),
                ]
            );
            abort(
                500,
                'Failed to load profile details.'
            );
        }
       
    }

    public function show(Profile $profile)
    {
        try {
            $profile->load([
                'snapshots' => fn ($query) =>
                    $query->latest('snapshot_at')
            ]);
            return Inertia::render(
                'profiles/Show',
                [
                    'profile' => $profile,
                    'snapshots' => $profile->snapshots,
                ]
            );

        } catch (\Throwable $e) {
            \Log::error(
                'Failed to load profile details',
                [
                    'profile_id' => $profile->id ?? null,
                    'message' => $e->getMessage(),
                ]
            );
            abort(
                500,
                'Failed to load profile details.'
            );
        }
    }
}
