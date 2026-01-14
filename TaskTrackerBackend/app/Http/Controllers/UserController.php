<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * index method:
     * Returns all methods from the users table
     */
    public function index()
    {
        log::info('Index method in the user controller has been reached');

        // Retrieve all records from the users table
        $users = User::all();

        log::info('All records from the users table have successfully been retrieved');

        // Return as a Json response with status code 200 because this backend is being used as an API
        return response()->json(["users" => $users], 200);
    }
}
