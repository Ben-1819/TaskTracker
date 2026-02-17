<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

describe('Tests to check that the login method works as intended', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => Hash::make('password'),
        ]);
    });

    it('tests that the login method works when valid data is entered and the account exists', function(){
        $response = $this->postJson('/api/login',[
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertJsonStructure([
            'token',
            'expires_in',
        ]);
    });

    it('tests that the login method doesn\'t work when the user enters invalid data and the account does\'t exist', function(){
        $response = $this->postJson('/api/login',[
            'email' => $this->user->email,
            'password' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'Password is a required field',
            ]);
    });

    it('tests that the login method works when the user enters invalid credentials and the account exists', function(){
        $response = $this->postJson('/api/login',[
            'email' => $this->user->email,
            'password' => 'PASSWORD',
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'invalid_credentials',
            ]);
    });

    it('tests that the login method doesn\'t work when the user enters valid data and the account doesn\'t exist', function(){
        $response = $this->postJson('/api/login', [
            'email' => 'example@email.com',
            'password' => 'Password',
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'invalid_credentials',
            ]);
    });

    it('tests that the login method doesn\'t work when the user enters invalid data and the account doesn\'t exist', function(){
        $response = $this->postJson('/api/login',[
            'email' => '',
            'password' => 'PASSWORD',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'Email is a required field',
            ]);
    });
})->group('LoginTests');

describe('Tests to check that the logout method works as intended', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password'=> 'password',
        ]);
    });

    it('tests that the correct message is returned when the logout method is successful', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertOk()
            ->assertJson([
                'logout_success' => 'Successfully logged out',
            ]);
    });

    it('tests that the logout method returns the correct status and error message when the logout method is invalid', function(){
        $this->withoutMiddleware();
        $token = JWTAuth::fromUser($this->user);

        JWTAuth::shouldReceive('getToken')->once()->andReturn($token);
        JWTAuth::shouldReceive('invalidate')->once()->with($token)->andThrow(new JWTException('Token invalidation failed'));

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(500)
            ->assertJson([
                'logout_error' => 'Failed to logout, please try again',
            ]);
    });
})->group('LogoutTests');

describe('Tests to check that the getUser method works as intended', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => 'password',
        ]);
    });

    it('tests that the getUser method works when there is a user logged in', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
            ]);
    });

    it('tests that the getUser method doesn\'t work when there is no user logged in', function(){
        $this->withoutMiddleware();

        $token = JWTAuth::fromUser($this->user);

        Auth::shouldReceive('user')->andReturn(null);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/user');

        $response->assertStatus(404)
            ->assertJson([
                'no_user' => 'User not found',
            ]);
    });

    it('tests that the getUser method doesn\'t work when the users JWT is invalid', function(){
        $this->withoutMiddleware();

        Auth::shouldReceive('user')->andThrow(new JWTException('Token error'));

        $response = $this->getJson('/api/user');

        $response->assertStatus(500)
            ->assertJson([
                'error' => 'Failed to fetch user profile',
            ]);
    });
})->group('GetUserTests');
