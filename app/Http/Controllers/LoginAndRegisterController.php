<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginAndRegisterController extends Controller
{
    public function login(Request $request)
    {
        $credential = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credential)) {
            return response()->json([
                'message' => 'login successful'
            ]);
        }

        return response()->json([
            'message' => 'login failed'
        ], 400);

        // $user = User::query()->where('login', $request->login)->first();

        // if ($user || Hash::check($request->password, $user->password) == false) {
        //     throw ValidationException::withMessages([
        //         'login' => ['The provided credentials are incorrect'],
        //     ]);
        // }

    }

    public function register(Request $request)
    {
        $data = $request->all();

        User::query()->create([
            'login' => $data['login'],
            'password' => Hash::make($data['password']),
        ]);

        return response('ok');
    }

    public function logout(){

    }
}
