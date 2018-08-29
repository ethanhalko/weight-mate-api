<?php

namespace App\Http\Controllers;

use App\User;
use App\WeightEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;


class UserController extends Controller
{
    /**
     * @var array|null|string
     */
    protected $user;
    /**
     * @var JWTAuth
     */
    protected $jwt;

    /**
     * UserController constructor.
     * @param Request $request
     * @param JWTAuth $jwt
     */
    public function __construct(Request $request, JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->user = $request->input('user');
    }

    public function index()
    {
        return response()->json(User::where('active', true)->get());
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'group_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'initial_weight' => 'required',
            'pin' => 'required',
            'pin_confirmation' => 'same:pin',
        ]);

        $user = User::create($input);
        $user->pin = Hash::make($input['pin']);

        WeightEntry::create([
            'user_id' => $user->id,
            'weight' => $request->input('initial_weight'),
        ]);

        $user->save();


        return response()->json([
            'user' => $user,
            'token' => $this->jwt->fromUser($user)
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $results = User::where('first_name', 'LIKE', '%' . $query . '%')
            ->orWhere('last_name', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json($results);
    }

    public function scan(Request $request)
    {
        $barcode = $request->get('barcode');

        return User::where('active', true)->where('barcode', $barcode);
    }
}
