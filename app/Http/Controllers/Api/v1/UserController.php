<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ParkingSpace;
use App\Models\User;
use App\Pipelines\DebitingBalance;
use App\Pipelines\FinishSession;
use App\Pipelines\OccupyParkingSpace;
use App\Pipelines\OpeningBarrier;
use App\Pipelines\ReleaseParkingSpace;
use App\Pipelines\StartSession;
use App\Pipelines\UpdateLanterns;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Pipeline;

class UserController extends Controller
{
    /**
     *  Scans the user's card
     */
    public function scanCard(User $user): JsonResponse
    {
        if (! (bool) $user->load('latestSession')->latestSession?->is_parked) {
            return response()->json([
                'is_parked' => false,
                'free_seats' => ParkingSpace::where('is_occupied', false)->count(),
            ]);
        }

        return response()->json([
            'is_parked' => true,
            'created_at' => $user->latestSession?->created_at->toDatetimeString(),
            'session_duration' => now()->diffInMinutes($user->latestSession?->created_at),
        ]);
    }

    /**
     *  Lets the user's car into the parking lot
     */
    public function letCar(User $user): JsonResponse
    {
        $user_data['user'] = $user;

        DB::beginTransaction();

        try {
            $user_data = Pipeline::send($user_data)
                ->through([
                    OpeningBarrier::class,
                    StartSession::class,
                    OccupyParkingSpace::class,
                    UpdateLanterns::class,
                ])->thenReturn();
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => ['error' => $e],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'name_space' => $user_data['parking_space']->name,
        ]);
    }

    /**
     *  Releases the user's car from the parking lot
     */
    public function releaseCar(User $user): JsonResponse
    {
        $user_data['user'] = $user;

        DB::beginTransaction();

        try {
            $user_data = Pipeline::send($user_data)
                ->through([
                    OpeningBarrier::class,
                    FinishSession::class,
                    ReleaseParkingSpace::class,
                    UpdateLanterns::class,
                    DebitingBalance::class,
                ])->thenReturn();
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => ['error' => $e],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::commit();

        return response()->json([
            'success' => true,
        ]);
    }
}
