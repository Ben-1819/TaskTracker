<?php
use Illuminate\Foundation\Testing\RefreshDatabase;

describe('Tests to check that the register function works as intended', function(){
    it('tests that users can registere for an account when using valid input data', function(){
        $response = $this->postJson('/api/register',[
            'first_name' => 'test',
            'last_name' => 'user',
            'email' => 'test@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'user',
            ]);
    });

    it('tests that users can\'t register for an account when invalid data is entered in the first_name field', function(){
        $response = $this->postJson('/api/register',[
            'first_name' => '',
            'last_name' => 'User',
            'email' => 'email@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name' => 'First name is a required field',
            ]);
    });

    it('tests that users can\'t register for an account when invalid data is entered in the last_name field', function(){
        $response = $this->postJson('/api/register',[
            'first_name' => 'Test',
            'last_name' => '',
            'email' => 'email@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'last_name' => 'Last name is a required field',
            ]);
    });

    it('tests that users can\'t register when invalid data is entered in the email field', function(){
        $response = $this->postJson('/api/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'Email is a required field',
            ]);
    });
})->group('RegisterTests');
