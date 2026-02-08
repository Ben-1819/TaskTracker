<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

describe('Tests to check that the error messages for the email field are correct when the validation rules are broken', function(){
    it('tests that the correct error message is returned when the email field is left blank', function(){
        $response = $this->postJson('/api/login', [
            'email' => '',
            'password'=> 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'Email is a required field',
            ]);
    });

    it('tests that the correct error message is returned when the email field is not a string value', function(){
        $response = $this->postJson('/api/login', [
            'email' => 12345,
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'Email must be of data type string',
            ]);
    });

    it('tests that the correct error message is returned when the email field is not an email value', function(){
        $response = $this->postJson('/api/login', [
            'email'=> 'ben',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'The email you enter must be a valid email'
            ]);
    });

    it('tests that the correct error message is returned when the email field is longer than 100 characters', function(){
        $faker = Faker\Factory::create();

        $response = $this->postJson('/api/login', [
            'email' => $faker->realTextBetween(96, 99) . '@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'Email can\'t exceed 100 characters'
            ]);
    });
})->group('LoginEmailErrors');

describe('Tests to check that the error messages for the password field are correct when validation rules are broken', function(){
    it('checks that the correct error message is returned when the password field is left empty', function(){
        $response = $this->postJson('/api/login', [
            'email' => 'example@gmail.com',
            'password'=> ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'Password is a required field'
            ]);
    });

    it('tests that the correct error message is returned when the password field is not a string value', function(){
        $response = $this->postJson('/api/login', [
            'email' => 'example@gmail.com',
            'password' => 123456
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'Password must be of data type string'
            ]);
    });

    it('tests that the correct error message is returned when the password field is less than 6 characters long', function(){
        $response = $this->postJson('/api/login', [
            'email' => 'example@gmail.com',
            'password' => 'pass'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'Password must be at least 6 characters'
            ]);
    });
})->group('LoginPasswordErrors');
