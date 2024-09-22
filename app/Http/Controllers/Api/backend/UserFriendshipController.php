<?php

namespace App\Http\Controllers\api\backend;

use App\Http\Controllers\Controller;
use App\Models\UserFriendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class UserFriendshipController extends Controller
{
    /**
     * Display a list of the authenticated user's friendships.
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            // Get all friendships
            $friendships = UserFriendship::where('sender_id', $userId)
                        ->orWhere('receiver_id', $userId)
                        ->orWhere('receiver_id', $userId)
                        ->orWhere('is_accecpt', $userId)
                        ->get();

            //return response
            return response()->json([
                'success' => true,
                'data' => $friendships,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to Get all friendships: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Send a friend request.
     */
    public function send_request($receiver_id)
    {
        // $validator = Validator::make($request->all(), [
        //     'receiver_id' => 'required|exists:users,id',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => $validator->errors()->first(),
        //         'success' => false
        //     ], 400);
        // }

        $sender_id = Auth::user();
        
        $now = Carbon::now();

        try {
            // Create a new friend request
            $friendship = UserFriendship::create([
                'sender_id' => 2, 
                'receiver_id' => $receiver_id,
                'is_accecpt' => 'pending',
                'action_date' => $now,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Friend request sent successfully!',
                'data' => $friendship,
                'sender_id' => $sender_id,
                
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sent Friend request: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Accept a friend request.
     */
    public function accept_request($id)
    {
        try {
            $friendship = UserFriendship::find($id);
                
            $friendship->update([
                'is_accecpt' => 'accepted',
                'action_date' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Friend request accepted!',
                'data' => $friendship,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept friend request: ' . $th->getMessage(),
            ], 500);
        }
    }


    /**
     * Reject a friend request.
     */
    public function rejectRequest($id)
    {
        try {
            $friendship = UserFriendship::where('receiver_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            $friendship->update([
                'status' => 'rejected',
                'action_date' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Friend request rejected!',
                'data' => $friendship,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject friend request: ' . $th->getMessage(),
            ], 500);
        }
    }



    /**
     * Remove a friend
     */
    public function unfriend($id)
    {
        try {
            $friendship = UserFriendship::where(function ($query) use ($id) {
                $query->where('sender_id', Auth::id())
                    ->orWhere('receiver_id', Auth::id());
            })
                ->where('id', $id)
                ->where('status', 'accepted')
                ->firstOrFail();
        
            $friendship->delete();

            return response()->json([
                'success' => true,
                'message' => 'Friend removed successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove friend: ' . $th->getMessage(),
            ], 500);
        }
    }
}
