<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index()
    {

    }

    public function store(StoreTaskRequest $request)
    {
    }

    public function show($id)
    {
    }

    public function update(UpdateTaskRequest $request, $id)
    {
    }

    public function complete($id)
    {
    }

    public function destroy($id)
    {
    }
}
