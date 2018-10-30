<?php

namespace App\Http\Controllers;

use App\Settings;
use App\User;
use App\WeightEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\JWTAuth;

class WeightEntryController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $authUser = $this->jwt->user();

        if ($authUser) {
            $entries = WeightEntry::where('user_id', $authUser->id)->get();
        }

        return response()->json(['user' => $authUser, 'entries' => $entries ?? []]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $user = User::find($request->input('user'));
        $weightEntry = new WeightEntry();
        $lastEntry = WeightEntry::where('user_id', $user->id)->latest()->first();
        Log::info('Weight entered: ' . $user->name ?? 'nope' . ' - ' . $request->input('weight'));

        if ($user) {
            if ($lastEntry && Carbon::parse($lastEntry->created_at)->isToday()) {
                WeightEntry::latest()->first()->delete();
            }

            $weightEntry = WeightEntry::create([
                'user_id' => $user->id,
                'weight' => $request->input('weight'),
            ]);
        }

        return response()->json(['weight' => $weightEntry ?? null, 'message' => $weightEntry->getMessage($user)]);
    }
}
