<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * @var array|null|string
     */
    protected $user;

    /**
     * GroupController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->user = $request->input('user');
    }

    /**
     * @return static
     */
    public function index()
    {
        return response()->json(Group::all()->where('active', true));
    }

    /**
     * @param $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function users($group)
    {
        $users = User::where('group_id', $group)
            ->where('active', true)
            ->get();

        $users = $users->map(function ($user) {
            return $user->only(['first_name', 'last_name', 'id', 'group_id']);
        });

        return response()->json($users);
    }
}
