<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\api\backend\CategoryController;

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




    /**
     * Category  Routes
     */
    Route::resource('categories',CategoryController::class);


});
