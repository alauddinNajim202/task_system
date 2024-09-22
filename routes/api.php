<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\backend\AuthController;
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

    // refresh
    route::post('/refresh', [AuthController::class, 'refresh']);



    // route for user profile show, user update , update password
    route::get('/profile/show', [AuthController::class, 'show']);
    route::post('/profile-update', [AuthController::class, 'profile_update']);
    route::post('/password-update', [AuthController::class, 'password_update']);



    // task fitler by category ways 
    route::get('/category/task/{category_id}', [TaskController::class, 'category_task']);





});