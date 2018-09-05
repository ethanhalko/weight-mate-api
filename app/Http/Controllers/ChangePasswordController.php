<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('auth.change-password');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function set(Request $request, User $user)
    {
        $passwordsMatch = collect(array_unique($request->only('new_password', 'new_password_confirm')))->count() < 2;
        dd(Hash::make($request->input('password')) == $user->password);

        if (Hash::make($request->input('password')) == $user->password || !$passwordsMatch) {
            route('password.index')->with('status', 'Something went wrong!');
        }


        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return route('logout')->with('status', 'Password Changed');
    }
}
