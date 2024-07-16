<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{


    /**
     * Display a listing of the resource without authenticate (testing purposes)
     */

    public function get_all_data(){
        return Todo::all();
    }

    public function get_tasks_by_id(){
        
        // Get user ID from the authenticated user

        $user_id = auth()->user()->id;

        // Get all tasks based on the user ID
        return Todo::where('user_id', $user_id)->get();

    }

    
    /**
     * Display listing of todos
     */

    public function index(Request $request, $todo = null)
    {
        if ($todo == null) {
            return Todo::with('user')->get();
        } else {
            return Todo::with('user')->find($todo);
        }
    }

    /**
     * Update the todos
     */
 
    public function update(Request $request)
    {

        $id = $request->id;
        $user_id = auth()->user()->id;
        $todo = Todo::find($id);


        if (!$todo) {
            // Todo Item not found
            return response()->json(['error' => 'Todo not found'], 404);
        }

        if ($todo->user_id != $user_id) {
            // Todo Item is found but not owned by the authenticated user
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->save();

        return response()->json(['message' => 'Todo updated successfully', 'todo' => $todo], 200);
    }

    /**
     * Toggle the status of the todos. (Checkbox)
     */

    public function update_status(Request $request){
        $id = $request->id;
        $status = $request->status;

        $todo = Todo::find($id);
        $todo->status = $status;
        $todo->save();

        return response()->json(['message' => 'Todo updated successfully', 'todo' => $todo], 200);

    }

    /**
     * Create a new todo
     */

    public function store(Request $request)
    {
        $todo = new Todo();
        // Check Authenticated User from Authorization



        $todo->user_id = Auth::id();

        // Read user_id from the authenticated user
        // $todo->user_id = auth()->user()->id;
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->status = $request->status;
        $todo->save();

        return response()->json(['message' => 'Todo created successfully', 'todo' => $todo, 'status' => 201]);
    }

    /**
     * Delete a todo
     */

    public function delete(Request $request){

        $user_id = auth()->user()->id;
        $id = $request->id;
        $todo = Todo::find($id);
        $todo->delete();

        return response()->json(['message' => 'Tasks deleted successfully']);

    }

}



