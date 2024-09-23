<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\api\backend\CategoryController;
use App\Http\Controllers\Api\backend\TaskController;
use App\Http\Controllers\Api\backend\UserFriendshipController;
use App\Http\Controllers\Api\backend\TaskAssignController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>'api','prefix'=>'auth'], function(){

    // route for user login and registration
    route::post('/register', [AuthController::class, 'register']);
    route::post('/login', [AuthController::class, 'login']);
    route::post('/logout', [AuthController::class, 'logout']);
    route::post('/refresh', [AuthController::class, 'refresh']);

    // route for user profile show, user update , update password
    route::get('/profile/show', [AuthController::class, 'show']);
    route::post('/profile-update', [AuthController::class, 'profile_update']);
    route::post('/password-update', [AuthController::class, 'password_update']);

    // tasks route
    route::get('/tasks', [TaskController::class, 'index']);
    route::post('/tasks/store', [TaskController::class, 'store']);
    route::get('/tasks/show/{id}', [TaskController::class, 'show']);
    route::put('/tasks/update/{id}', [TaskController::class, 'update']);
    route::delete('/tasks/delete/{id}', [TaskController::class, 'destroy']);
    // task fitler by category ways 
    route::get('/category/task/{category_id}', [TaskController::class, 'category_task']);


    // task details route
    route::get('/task-details/{id}', [TaskAssignController::class, 'tast_details']);

    route::post('/task_details_update/{id}', [TaskAssignController::class, 'tast_details_update']);



    // route::post('/tasks/store', [TaskController::class, 'store']);
    // route::get('/tasks/show/{id}', [TaskController::class, 'show']);
    // route::put('/tasks/update/{id}', [TaskController::class, 'update']);
    // route::delete('/tasks/delete/{id}', [TaskController::class, 'destroy']);


    /**
     * Category  Routes
     */
    Route::resource('categories',CategoryController::class);

    /**
     * User Friendship
     */
    Route::get('/friendships', [UserFriendshipController::class, 'index']);
    Route::post('/friendships/request/{receiver_id}', [UserFriendshipController::class, 'send_request']);
    Route::post('/friendships/accept/{id}', [UserFriendshipController::class, 'accept_request']);
    Route::post('/friendships/reject/{id}', [UserFriendshipController::class, 'rejectRequest']);
    Route::delete('/friendships/unfriend/{id}', [UserFriendshipController::class, 'unfriend']);

});
