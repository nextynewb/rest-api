<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation of Data
        $data = request()->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        // Hashing the password
        $data['password'] = bcrypt($data['password']);


        // Check if the user already exists
        if (User::where('email', $data['email'])->exists()) {
            return response(['message' => 'User already exists']);
        }
        else{
        // Create a new user
        $user = User::create($data);

        // Create a token for the user
        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken]);

        }

    }

    public function login()
    {
        $data = request()->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if (!auth()->attempt($data)) {
            return response(['message' => 'Invalid credentials', 'status' => 401]);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken, 'status' =>200]);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();
        return response(['message' => 'Successfully logged out', 'status' => 200]);
    }

}
