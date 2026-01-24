<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        log::info("Register function triggered");

        // Validate the users input & log it
        $request->validated();
        log::info($request);

        // Create a new user & save it
        $user = new User([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $user->save();

        log::info('New user successfully created');
        log::info('First name: {first_name}', ['first_name' => $user->first_name]);
        log::info('Last name: {last_name}', ['last_name' => $user->last_name]);
        log::info('Email: {email', ['email' => $user->email]);

        // Create a JWT for the user
        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            log::error('There was a problem creating the user\'s JWT {error}', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Could not create the user\'s JWT'], 500);
        }

        // Return the token and the user
        return response()->json([
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {

    }

    public function logout()
    {

    }

    public function getUser()
    {

    }
}
