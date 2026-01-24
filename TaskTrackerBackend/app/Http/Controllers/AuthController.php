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
        log::info('Login function triggered');

        // Create a variable called credentials and set it to the username and password
        $credentials = $request->only(['email', 'password']);

        // Try to log the user in
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                // Return a JSON response telling the user the credentials were invalid
                log::info('User\'s credentials are invalid');
                return response()->json(['invalid_credentials' => 'The username or password entered was incorrect'], 401);
            }
        } catch (JWTException $e) {
            log::error('There was an error logging in: {error}', ['error' => $e->getMessage()]);
            // Return a response telling the user that a token could not be made
            return response()->json(['token_error' => 'Could not create token'], 500);
        }

        // Return the token and when it expires
        return response()->json([
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        log::info('Logout function triggered');
        // Try to invalidate the JWT currently held by the user
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            log::error('There was an error logging out: {error}', ['error' => $e->getMessage()]);
            return response()->json(['logout_error' => 'Failed to logout, please try again'], 500);
        }

        // Return a json response saying the user is successfully logged out
        return response()->json(['logout_success' => 'Successfully logged out']);
    }

    public function getUser()
    {
        log::info('getUser function triggered');
        // use a try-catch block to see if there is a user logged in
        try {
            $user = Auth::user();
            // If there is no user logged in
            if (!$user) {
                log::info('There is no user currently logged in');
                return response()->json(['no_user' => 'User not found'], 404);
            }
            log::info('There is a user logged in');
            // Return a JSON response with logged in user
            return response()->json($user);
        } catch (JWTException $e) {
            log::error('There is an error checking if a user is logged in: {error}', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch user profile'], 500);
        }
    }
}
