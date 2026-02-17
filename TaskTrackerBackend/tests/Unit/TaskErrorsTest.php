<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

describe('Tests the correct error messages are returned when the validation rules for the name field are broken when creating a task', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => Hash::make('password'),
        ]);
    });

    it('tests the correct error message is returned when the name field is left empty while creating a task', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/store', [
                'name' => '',
                'description' => 'example description',
                'category' => 'housework',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'Name is a required field',
            ]);
    });

    it('tests the correct error message is returned when the name field is not a string value while creating a task', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization',"Bearer $token")
            ->postJson('/api/store', [
                'name'=> 12345,
                'description' => 'example description',
                'category' => 'housework',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'Name must be a string',
            ]);
    });

    it('tests the correct error message is returned when the name field is greater than 100 characters while creating a task', function(){
        $faker = Faker\Factory::create();
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/store', [
                'name' => $faker->realTextBetween(101, 105),
                'description' => 'example description',
                'category' => 'housework',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'Name must be 100 characters or less',
            ]);
    });
})->group('TaskNameStoreErrors');

describe('Tests the correct error messages are returned when the validation rules for the description field are broken when creating a task', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => Hash::make('password'),
        ]);
    });

    it('tests the correct error message is returned when the description field is left empty while creating a task', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/store', [
                'name' => 'example name',
                'description' => '',
                'category' => 'housework',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'description' => 'Description is a required field',
            ]);
    });

    it('tests the correct error message is returned when the description field is not a string value while creating a task', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/store', [
                'name' => 'example name',
                'description' => 12345,
                'category' => 'housework',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'description' => 'Description must be a string',
            ]);
    });

    it('tests the correct error message is returned when the description field is greater than 255 characters while creating a task', function(){
        $faker = Faker\Factory::create();
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/store', [
                'name' => 'example name',
                'description' => $faker->realTextBetween(260, 265),
                'category' => 'housework',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'description' => 'Description must be 255 characters or less',
            ]);
    });
})->group('TaskDescriptionStoreErrors');

describe('Tests the correct error messages are returned when the validation rules for the category field are broken when creating a task', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => Hash::make('password'),
        ]);
    });

    it('tests the correct error message is returned when the category field is left empty while creating a task', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/store', [
                'name' => 'example name',
                'description' => 'example description',
                'category' => '',
                'date_due'=> Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'category' => 'Category is a required field',
            ]);
    });

    it('tests the correct error message is returned when the category field is not a string value while creating a task', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization',"Bearer $token")
            ->postJson('/api/store', [
                'name' => 'example name',
                'description' => 'example description',
                'category' => 12345,
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'category'=> 'Category must be of data type string',
            ]);
    });
})->group('TaskCategoryStoreErrors');

describe('Tests the correct error messages are returned when the validation rules for the date_due field are broken when creating a task', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => Hash::make('password'),
        ]);
    });

    it('tests the correct error message is returned when the date_due field is left empty when creating a task', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization',"Bearer $token")
            ->postJson('/api/store', [
                'name' => 'example name',
                'description' => 'example description',
                'category' => 'housework',
                'date_due' => null,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'date_due' => 'Date due is a required field',
            ]);
    });

    it('tests the correct error message is returned when the date_due field is not a date value when creating a task', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization',"Bearer $token")
            ->postJson('/api/store', [
                'name' => 'example name',
                'description' => 'example description',
                'category' => 'housework',
                'date_due' => 'date',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'date_due'=> 'Date due must be a valid date',
            ]);
    });

    it('tests the correct error message is returned when the date_due field is not in the future when creating a task', function(){
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization',"Bearer $token")
            ->postJson('/api/store', [
                'name' => 'example name',
                'description' => 'example description',
                'category' => 'housework',
                'date_due' => Date::yesterday(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'date_due' => 'Date due must be a date in the future',
            ]);
    });
})->group('TaskDueDateStoreErrors');

describe('Tests the correct error messages are returned when the validation rules for the name field are broken when updating a task', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => Hash::make('password'),
        ]);

        $this->task = createTask($this->user);
    });

    it('tests the correct error message is returned when the name field is left blank when updating a task', function(){
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization',"Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => '',
                'description' => 'example description',
                'category' => 'example',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'Name is a required field',
            ]);
    });

    it('tests the correct error message is returned when the name field is not a string value when updating a task', function(){
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 12345,
                'description' => 'example description',
                'category' => 'example',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'Name must be of data type string',
            ]);
    });

    it('tests the correct error message is returned when the name field is greater than 100 characters when updating a task', function(){
        $faker = Faker\Factory::create();
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization',"Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => $faker->realTextBetween(101, 105),
                'description' => 'example description',
                'category' => 'example',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'Name must be 100 characters or less',
            ]);
    });
})->group('TaskNameUpdateErrors');

describe('Tests the correct error messages are returned when the validation rules for the description field are broken when updating a task', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => Hash::make('password'),
        ]);

        $this->task = createTask($this->user);
    });

    it('tests the correct error messages are returned when the description field is left blank when updating a task', function(){
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name',
                'description' => '',
                'category' => 'example category',
                'due_date' => Date::tomorrow(),
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'description' => 'Description is a required field',
                ]);
    });

    it('tests the correct error message is returned when the description field is not a string value when updating a task', function(){
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name',
                'description' => 12345,
                'category' => 'example category',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'description' => 'Description must be of data type string',
            ]);
    });

    it('tests the correct error message is returned when the description field is greater than 255 characters when updating a task', function(){
        $faker = Faker\Factory::create();
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name',
                'description' => $faker->realTextBetween(260, 265),
                'category' => 'example category',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertjsonValidationErrors([
                'description'=> 'Description must be 255 characters or less',
            ]);
    });
})->group('TaskDescriptionUpdateErrors');

describe('Tests the correct error messages are returned when the validation rules for the category field are broken when updating a task', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => Hash::make('password'),
        ]);

        $this->task = createTask($this->user);
    });

    it('tests the correct error message is returned when the category field is left blank when updating a task', function(){
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name',
                'description' => 'example description',
                'category' => '',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'category' => 'Category is a required field',
            ]);
    });

    it('tests the correct error message is returned when the category field is not a string value when updating a task', function(){
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name',
                'description' => 'example description',
                'category' => 12345,
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'category' => 'Category must be of data type string',
            ]);
    });
})->group('TaskCategoryUpdateErrors');

describe('Tests the correct error messages are returned when the validation rules for the date_due field are broken when updating a task', function(){
    beforeEach(function(){
        $this->user = User::factory()->createOne([
            'password' => Hash::make('password'),
        ]);

        $this->task = createTask($this->user);
    });

    it('tests the correct error message is returned when the date_due field is left blank when updating a task', function(){
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name',
                'description' => 'example description',
                'category' => 'example category',
                'date_due' => null,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'date_due' => 'Date due is a required field',
            ]);
    });

    it('tests the correct error message is returned when the date_due field is not a date value when updating a task', function(){
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name',
                'description' => 'example description',
                'category' => 'example category',
                'date_due' => 'not a date',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'date_due' => 'Date due must be a valid date',
            ]);
    });

    it('tests the correct error message is returned when the date_due field is not in the future when updating a task', function(){
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name',
                'description' => 'example description',
                'date_due' => Date::yesterday(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'date_due' => 'Date due must be in the future',
            ]);
    });
})->group('TaskDueDateUpdateErrors');
