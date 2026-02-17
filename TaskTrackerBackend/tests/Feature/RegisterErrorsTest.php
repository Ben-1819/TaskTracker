<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses (RefreshDatabase::class);

describe('Tests to check that the error messages are correct whenever the validation rules for the first_name field are broken', function(){
    it('tests that the correct error message is returne when the first_name field is left empty', function(){
        $response = $this->postJson('/api/register', [
            'first_name' => '',
            'last_name' => 'last name',
            'email' => 'example@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name'=> 'First name is a required field',
            ]);
    });

    it('tests that the correct error message is returned when the first_name field is not a string', function(){
        $response = $this->postJson('/api/register', [
            'first_name' => 12345,
            'last_name' => 'last name',
            'email' => 'example@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name' => 'First name must be of data type string',
            ]);
    });

    it('tests that the correct error message is returned when the first_name field is longer than 55 characters', function(){
        $faker = Faker\Factory::create();

        $response = $this->postJson('/api/register', [
            'first_name' => $faker->realTextBetween(56, 60),
            'last_name' => 'last name',
            'email' => 'example@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name' => 'First name must not exceed 55 characters',
            ]);
    });
})->group('RegisterFirstNameErrors');

describe('Tests to check that the error messages are correct whenever the validation rules for the last_name field are broken', function(){
    it('tests that the correct error message is returned when the last_name field is empty', function(){
        $response = $this->postJson('/api/register', [
            'first_name' => 'first name',
            'last_name' => '',
            'email' => 'example@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'last_name' => 'Last name is a required field',
            ]);
    });

    it('tests that the correct error message is returned when the last_name field is not a string', function(){
        $response = $this->postJson('/api/register', [
            'first_name' => 'first name',
            'last_name' => 12345,
            'email' => 'example@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'last_name' => 'Last name must be of data type string',
            ]);
    });

    it('tests that the correct error message is returned when the last_name field is longer than 55 characters', function(){
        $faker = Faker\Factory::create();

        $response = $this->postJson('/api/register', [
            'first_name' => 'first name',
            'last_name' => $faker->realTextBetween(56, 60),
            'email' => 'example@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'last_name' => 'Last name must not exceed 55 characters',
            ]);
    });
})->group('RegisterLastNameErrors');

describe('Tests to check that the error messages are correct whenever the validation rules for the email field are broken', function(){
    it('tests that the correct error message is returned when the email field is empty', function(){
        $response = $this->postJson('/api/register', [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => '',
            'password'=> 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'Email is a required field',
            ]);
    });

    it('tests that the correct error message is returned when the email field is not a string', function(){
        $response = $this->postJson('/api/register', [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 12345,
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'Email must be of data type string',
            ]);
    });

    it('tests that the correct error message is returned when the email field is not a valid email', function(){
        $response = $this->postJson('/api/register', [
            'first_name'=> 'first name',
            'last_name'=> 'last name',
            'email' => 'example',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'The email entered must be a valid email',
            ]);
    });

    it('tests that the correct error message is returned when the email field is already in use', function(){
        $user = User::factory()->createOne([
            'email' => 'usedexample@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'first_name'=> 'first name',
            'last_name' => 'last_name',
            'email' => 'usedexample@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'This email already has an account connected to it',
            ]);
    });

    it('tests that the correct error message is returned when the email field is longer than 100 characters', function(){
        $faker = Faker\Factory::create();

        $response = $this->postJson('/api/register', [
            'first_name' => 'first name',
            'last_name' => 'last_name',
            'email' => $faker->realTextBetween(97, 100) . '@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'Email must not exceed 100 characters',
            ]);
    });
})->group('RegisterEmailErrors');

describe('Tests to check that the error messages are correct whenever the validation rules for the password field are broken', function(){
    it('tests that the correct error message is returned when the password field is empty', function(){
        $response = $this->postJson('/api/register', [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 'example@example.com',
            'password' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'Password is a required field',
            ]);
    });

    it('tests that the correct error message is returned when the password field is not a string', function(){
        $response = $this->postJson('/api/register', [
            'first_name'=> 'first name',
            'last_name'=> 'last name',
            'email' => 'example@example.com',
            'password' => 123456,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'Password must be of data type string',
            ]);
    });

    it('tests that the correct error message is returned when the password field is less than 6 characters', function(){
        $response = $this->postJson('/api/register', [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 'example@example.com',
            'password' => 'pass',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'Password must be at least 6 characters',
            ]);
    });
})->group('RegisterPasswordErrors');
