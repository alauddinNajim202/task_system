<?php

namespace App\Http\Controllers\Api\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register','login']]);
    }

     /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */ 
    // register function
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'token' => $token
        ], 201);
    }

    // login function
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid credentials',
                'status' => false,

            ], 401);
        }


        
        return response()->json([
            'success' => true,
            'message' => 'User login successfully',
            'token' => $token
        ], 201);
    }


    // user show function
    public function show(){

        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred'
            ], 500);
        }

    }

    // user profile update function
    public function profile_update(Request $request){

        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
            // 'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        // if ($request->hasFile('profile_image')) {
        //     $profileImagePath = Helper::fileUpload($request->file('profile_image'), 'profile_images', $user->name);
        //     $user->profile_image = $profileImagePath;
        // }

        $user->update($request->only('name', 'email'));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);

    }

    // user password update function
    public function password_update(Request $request){

        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        // checking user password and old password
        if(!Hash::check($request->old_password, $user->password)   ){
            return response()->json([
                'success' => false,
                'message' => 'The old password is incorrect'
            ], 400);
        }

        // update new password
        $user->password = Hash::make($request->new_password);
        $user->save();
        

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
            
        ]);

    }

    // user logout
    public function logout(){

        Auth::logout();

        return response()->json([
            
            'message' => 'User logout successfully',
            
        ]);

    }

    // user refresh
    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            
            return response()->json([
                'success' => true,
                'token' => $newToken
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh token'
            ], 500);
        }
}



}
