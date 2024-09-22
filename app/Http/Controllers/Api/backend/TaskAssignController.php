<?php

namespace App\Http\Controllers\Api\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskAssign;
class TaskAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function tast_details($id)
    {
        try {

            $task = TaskAssign::get_task($id);

            return response()->json([
                'success' => true,
                'message' => "Task details show",
                'name' => $task->name,
                'description' => $task->description,
                'Assigned friends' => $task->assignee_to,
                'status' => $task->status == 0 ? "InProgress" : 'Done',
                'End Date' => $task->due_date
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
