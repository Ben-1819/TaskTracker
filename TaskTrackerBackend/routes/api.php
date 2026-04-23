<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::controller(AuthController::class)->middleware('jwt')->group(function () {
    Route::get('/user', 'getUser');
    Route::post('/logout', 'logout');
});

Route::controller(UserController::class)->middleware('jwt')->group(function () {
    Route::get('/userIndex', 'index');
});

Route::controller(TaskController::class)->middleware('jwt')->group(function () {
    Route::get('/index', 'index');
    Route::get('/completed', 'completedTasks');
    Route::get('/incomplete', 'incompleteTasks');
    Route::get('/current', 'currentTasks');
    Route::post('/store', 'store');
});

Route::controller(TaskController::class)->middleware(['jwt', 'taskOwner'])->group(function () {
    Route::get("/{id}/show", 'show');
    Route::put("/{id}/update", 'update');
    Route::put("/{id}/complete", 'complete');
    Route::delete("/{id}/delete", 'destroy');
});
