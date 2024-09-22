<?php

namespace App\Http\Controllers\Api\backend;

use App\Models\Task;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        try {

            $tasks = Task::get_all_tasks();

            return response()->json([
                'success' => true,
                'tasks' => $tasks
            ], 201);


         } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error'   => $e->getMessage()
            ], 500);

        }


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' =>'required',
            'assignee_to' =>'required',
            
            'description' =>'required',
            'status' =>'required',
            'due_date' =>'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        try {

            $user_id = Auth::users()->id;

            $task = Task::create([
                'name' => $request->name,
                'user_id' => $user_id,
                'category_id' => $request->category_id,
                'assignee_to' => $request->assignee_to,
                'description' => $request->description,
                'status' => $request->status,
                'due_date' => $request->due_date,
                
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task
            ], 201);

               
           
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            $task = Task::get_single_task($id);

            return response()->json([
                'success' => true,
                'task' => $task
            ], 201);


         } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error'   => $e->getMessage()
            ], 500);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' =>'required',
            'assignee_to' =>'required',
            
            'description' =>'required',
            'status' =>'required',
            'due_date' =>'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        try {

            $task = Task::get_single_task($id);

            // task check
            if(is_null($task)){
                return response()->json(['message' => 'Task not found'], 404);
            }
            $user_id = Auth::users()->id;

            $task->update([
                'name' => $request->name,
                'user_id' => $user_id,
                'category_id' => $request->category_id,
                'assignee_to' => $request->assignee_to,
                'description' => $request->description,
                'status' => $request->status,
                'due_date' => $request->due_date,
                
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $task
            ], 201);

               
           
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            $task = Task::get_single_task($id);
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully',

            ], 201);


         } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error'   => $e->getMessage()
            ], 500);

        }
    }

    // filter by category in task

    public function category_task($category_id){

            try {

                $tasks = Task::get_task_by_category($category_id);

                return response()->json([
                    'success' => true,
                    'task' => $tasks
                ], 201);


            } catch (Exception $e) {

                return response()->json([
                    'success' => false,
                    'message' => 'Error occurred while processing your request',
                    'error'   => $e->getMessage()
                ], 500);

            }

    }
}
