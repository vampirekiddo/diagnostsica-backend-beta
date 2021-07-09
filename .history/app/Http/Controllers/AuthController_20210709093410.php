<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    protected function generateAccessToken($user)
    {
        $token = $user->createToken($user->email . '-' . now());
    }
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', '=', $request->email)->first();
        $user = User::create([
            'username' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json($user);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();
            
            $userid = Auth::user()->id;
            

            return response()->json([
                'user' => $user->username,
                'result' => $user->result,
                'id' => $user->id
            ]);
        } else {
            return response()->json([
                'Message' => "Email or Password is incorrect"

            ]);
        }
    }
}
