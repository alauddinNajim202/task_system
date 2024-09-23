<?php

namespace App\Http\Controllers\Api\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\TaskAssign;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class TaskAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function tast_details($id)
    {


        try {
            // dd($id);
            $task = Task::with('user')->find($id);
            // dd($task->user[0]->name);

            $assigned_friend =   $task->user ? $task->user[0]->name : "Not Assigned friend";

            return response()->json([
                'success' => true,
                'message' => "Task details show",
                'name' => $task->name,
                'description' => $task->description,
                'Assigned friends' => $assigned_friend,
                'status' => $task->status == 0 ? "InProgress" : 'Done',
                'End Date' => $task->due_date,
                // 'task' => $task

            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a task details newly created resource in storage.
     */
    public function tast_details_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'assigned_from' => 'required',
            'assigned_by' => 'required',
            'points' => 'required',
            'end_date' => 'required',

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
            if (is_null($task)) {
                return response()->json(['message' => 'Task not found'], 404);
            }


            // task details table update
            $task_detail = TaskDetail::create([
                'user_id' => $request->user_id,
                'task_id' => $task->id,
                'points' => $request->points,
                'end_date' => $request->end_date,


            ]);

            // task assign table update
            $task_assign = TaskAssign::create([

                'task_id' => $task->id,
                'assigned_from' => $request->assigned_from,
                'assigned_by' => $request->assigned_by,


            ]);


            // user total points update
            $user = User::with('task_details', 'user_level')->find($request->user_id);

            $total_points = $user->task_details->sum('points');

            $user->total_points = +$total_points;
            $user->save();


            if (empty($user->user_level)) {

                $task_assign = $user->user_level()->create([

                    'user_id' => $user->id,
                    'level' => 1, // initialy user level 1
                    'assigned_by' => $request->assigned_by,
                ]);
            } else {
                // user level increment

                $levels = [
                    100 => 2,  // Level 2: 101-200 points
                    200 => 3,  // Level 3: 201-300 points
                    300 => 4,  // Level 4: 301-400 points
                    400 => 5,  // Level 5: 401-500 points
                ];
                
                foreach ($levels as $min_points => $level) {
                    if ($total_points > $min_points && $total_points <= $min_points + 100) {
                        $user->user_level()->update(['level' => $level]);
                        break; 
                    }
                }
            }


            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task_detail' => $task_detail,
                'task_assign' => $task_assign,
                'user' => $user,
                // 'total_points' => $total_points,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
