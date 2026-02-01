<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskOwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the task id from the route parameters
        $taskId = $request->route('id');

        Log::info('The task id is: {taskId}', ['taskId' => $taskId]);

        // Find the record in the tasks table with a matching id
        $task = Task::find($taskId);

        // If the task doesn't exist in the table return an error
        if (!$task) {
            Log::error('Task with id: {taskId} does not exist', ['taskId' => $taskId]);
            // Return a json response with an error message
            return response()->json([
                'error' => 'Task could not be found',
            ], 404);
        }

        // Use an if statement to check if the current user is the owner of the task
        if (Auth::user()->id === $task->user_id) {
            Log::info('The current user is the owner of the task');
            return $next($request);
        } else {
            Log::info('The current user is not the owner of the task');
            return response()->json([
                'error' => 'You are not authorised to perform this action',
            ], 403);
        }
    }
}
