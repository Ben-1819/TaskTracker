<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

describe('Tests to check that the index method in TaskController works as intended', function () {
    beforeEach(function () {
        $this->user = User::factory()->createOne();
    });

    it('tests  the index method works when there is a logged in user', function () {
        createTask($this->user);
        $token = JwtAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/index');

        $response->assertStatus(200)
            ->assertJsonStructure(['all_tasks']);
    });

    it('tests the index method doesn\'t work when there is no user logged in', function () {
        createTask($this->user);

        $response = $this->getJson('/api/index');

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Token not found or malformed',
            ]);
    });
})->group('TaskIndexTests');

describe('Tests to check the store method in TaskController works as intended', function () {
    beforeEach(function () {
        $this->user = User::factory()->createOne();
    });

    it('tests the store method works when valid data is used and there is a user logged in', function () {
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/store', [
                'name' => 'example name',
                'description' => 'example description',
                'category' => 'example category',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => 'Task successfully created',
            ]);
    });

    it('tests the store method doesn\'t work when invalid data is entered and there is a user logged in', function () {
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/store', [
                'name' => 12345,
                'description' => 'example description',
                'category' => 'example category',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'Name must be a string',
            ]);
    });

    it('tests the store method doesn\'t work when valid data is entered and there is no user logged in', function () {
        $response = $this->postJson('/api/store', [
            'name' => 'example name',
            'description' => 'example description',
            'category' => 'example category',
            'date_due' => Date::tomorrow(),
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Token not found or malformed',
            ]);
    });
})->group('TaskStoreTests');

describe('Tests to check the show method in TaskController works as intended', function () {
    beforeEach(function () {
        $this->user = User::factory()->createOne();
        $this->task = createTask($this->user);
    });

    it('tests the show method works when the task being shown exists and there is a user logged in', function () {
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/$taskId/show");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'task'
            ]);
    });

    it('tests the show method doesn\'t work when the task being shown doesn\'t exist and the user is logged in', function () {
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/400/show");

        $response->assertStatus(404);
    });

    it('tests the show method doesn\'t work when the task being shown exists and there is no user logged in', function () {
        $taskId = $this->task->id;

        $response = $this->getJson("/api/$taskId/show");

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Token not found or malformed',
            ]);
    });

    it('tests the show method doesn\'t work when there is a user logged in but the task doesn\'t belong to them', function () {
        $user2 = User::factory()->createOne();
        $token = JWTAuth::fromUser($user2);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/$taskId/show");

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'You are not authorised to perform this action',
            ]);
    });
})->group('TaskShowTests');

describe('Tests to check that the update method in TaskController works as intended', function () {
    beforeEach(function () {
        $this->user = User::factory()->createOne();
        $this->task = createTask($this->user);
    });

    it('tests the update works when valid data is entered and the user is logged in', function () {
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name 2',
                'description' => 'example description 2',
                'category' => 'example category 2',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => 'Task updated successfully',
            ]);
    });

    it('tests the update method doesn\'t work when valid data is entered but the task doesn\'t exist', function () {
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/500/update", [
                'name' => 'example name 2',
                'description' => 'example description 2',
                'category' => 'example category 2',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Task could not be found',
            ]);
    });

    it('tests the update method doesn\'t work when invalid data is entered and the user is logged in', function () {
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => '',
                'description' => 'example description 2',
                'category' => 'example category 2',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'Name is a required field',
            ]);
    });

    it('tests the update method doesn\'t work when valid data is entered and there is no user logged in', function () {
        $taskId = $this->task->id;

        $response = $this->putJson("/api/$taskId/update", [
            'name' => 'example name 2',
            'description' => 'example description 2',
            'category' => 'example category 2',
            'date_due' => Date::tomorrow(),
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Token not found or malformed',
            ]);
    });

    it('tests the update method doesn\'t work when valid data is used but the task isn\'t owned by the logged in user', function () {
        $user2 = User::factory()->createOne();
        $token = JWTAuth::fromUser($user2);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/update", [
                'name' => 'example name 2',
                'description' => 'example description 2',
                'category' => 'example category 2',
                'date_due' => Date::tomorrow(),
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'You are not authorised to perform this action',
            ]);
    });
})->group('TaskUpdateTests');

describe('Tests to check that the complete method in TaskController works as intended', function () {
    beforeEach(function () {
        $this->user = User::factory()->createOne();
        $this->task = createTask($this->user);
    });

    it('tests the complete method works when the user is logged in and owns the task', function () {
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/complete", [
                'complete' => true
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => 'Task successfully set to complete',
            ]);
    });

    it('tests the complete method doesn\'t work when there is a user logged in but they don\'t own the task', function () {
        $user2 = User::factory()->createOne();
        $token = JWTAuth::fromUser($user2);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/$taskId/complete", [
                'complete' => true,
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'You are not authorised to perform this action'
            ]);
    });

    it('tests the complete method doesn\'t work when there is no user logged in', function () {
        $taskId = $this->task->id;

        $response = $this->putJson("/api/$taskId/complete", [
            'complete' => true,
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Token not found or malformed',
            ]);
    });

    it('tests the complete method doesn\'t work when the task being set to complete doesn\'t exist', function () {
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/5000/complete", [
                'complete' => true,
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Task could not be found',
            ]);
    });
})->group('TaskCompleteTests');

describe('Tests to check that the destroy method in TaskController works as intended', function () {
    beforeEach(function () {
        $this->user = User::factory()->createOne();
        $this->task = createTask($this->user);
    });

    it('tests the destroy method works when the user is logged in and owns the task', function () {
        $token = JWTAuth::fromUser($this->user);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/$taskId/delete");

        $response->assertStatus(200)
            ->assertJson([
                'success' => 'Task successfully deleted',
            ]);
    });

    it('tests the destroy method doesn\'t work when the user is logged in but the task doesn\'t belong to them', function () {
        $user2 = User::factory()->createOne();
        $token = JWTAuth::fromUser($user2);
        $taskId = $this->task->id;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/$taskId/delete");

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'You are not authorised to perform this action',
            ]);
    });

    it('tests the destroy method doesn\'t work when the task doesn\'t exist', function () {
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/5000/delete");

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Task could not be found',
            ]);
    });

    it('tests the destroy method doesn\'t work when the task exists but there is no user logged in', function(){
        $taskId = $this->task->id;

        $response = $this->deleteJson("/api/$taskId/delete");

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Token not found or malformed',
            ]);
    });
})->group('TaskDestroyTests');
