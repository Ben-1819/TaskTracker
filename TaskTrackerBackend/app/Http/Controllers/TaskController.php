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
        log::info('Index method running');
        $tasks = Task::where('user_id', Auth::user()->id);
        return response()->json([
            'all_tasks' => $tasks
        ], 200);
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
