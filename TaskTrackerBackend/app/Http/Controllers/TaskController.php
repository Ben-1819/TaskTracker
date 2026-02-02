<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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
        Log::info('Store function in TaskController running');

        // Validate the users input
        $request->validated();

        // Create a new record in the task table
        $task = new Task([
            'user_id' => Auth::user()->id,
            'name' => $request['name'],
            'description' => $request['description'],
            'category' => $request['category'],
            'date_set' => Carbon::now(),
            'date_due' => $request['date_due'],
        ]);

        $task->save();

        Log::info('New task saved');
        Log::info('Task name: {taskName}', ['taskName' => $task->name]);
        Log::info('Task description {taskDescription}', ['taskDescription' => $task->description]);
        Log::info('Task category: {taskCategory}', ['taskCategory' => $task->category]);
        Log::info('Date set: {dateSet}', ['dateSet' => $task->date_set]);
        Log::info('Date due: {dateDue}', ['dateDue' => $task->date_due]);

        // Return a json response saying the task was successfully created
        return response()->json([
            'success' => 'Task successfully created'
        ], 201);
    }

    public function show($id)
    {
        Log::info('Show method in task controller running');

        // Get the task that has a matching id
        $task = Task::find($id);

        // Return the task in a json response
        return response()->json([
            'task' => $task
        ], 200);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        Log::info('update fuction in task controller running');

        // Validate the users input
        $request->validated();

        Log::info('The users input is valid');

        // Get the current task record
        $oldTask = Task::find($id);

        // Update the task
        $task = Task::where('id', $id)->update([
            'user_id' => $oldTask->user_id,
            'name' => $request['name'],
            'description' => $request['description'],
            'category' => $request['category'],
            'date_set' => $oldTask->date_set,
            'date_due' => $request['date_due'],
            'complete' => $oldTask->complete
        ]);
        Log::info('Task updated successfully');

        // Return a success message with a json response
        return response()->json([
            'success' => 'Task updated successfully'
        ], 200);
    }

    public function complete(Request $request, $id)
    {
        Log::info('complete function in task controller running');

        $validated = $request->validated([
            'complete' => ['required', 'boolean'],
        ]);

        // Find the record of the task that is being set to complete
        $task = Task::findOrFail($id);

        // Update the task to be complete
        $task->update([
            'complete' => $validated['complete'],
        ]);
        Log::info('Task completion status updated', [
            'task_id' => $task->id,
            'complete' => $validated['complete']
        ]);

        // Return a success message to the user
        return response()->json([
            'success' => 'Task successfully set to complete',
        ], 200);
    }

    public function destroy($id)
    {
    }
}
