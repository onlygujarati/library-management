<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "user_type", "status"},
     *             @OA\Property(property="name", type="string", example="jeck"),
     *             @OA\Property(property="email", type="string", format="email", example="test@demo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *             @OA\Property(property="user_type", type="string", example="user"),
     *             @OA\Property(property="status", type="string", example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registration successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="jeck"),
     *                 @OA\Property(property="email", type="string", example="test@demo.com"),
     *                 @OA\Property(property="user_type", type="string", example="user"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="api_token", type="string", example="1|AbCdEfGhIjKlMnOpQrStUvWxYz")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email field is required.")
     *         )
     *     )
     * )
     */

    public function register(UserRequest $request)
    {
        $input = $request->all();
     
        $password = $input['password'];
        $input['user_type'] = isset($input['user_type']) ? $input['user_type'] : 'user';
        $input['password'] = Hash::make($password);

        $user = User::create($input);
        $user->assignRole($input['user_type']);

        $message = __('message.save_form', ['form' => __('message.data')]);
        $user->api_token = $user->createToken('auth_token')->plainTextToken;
        $response = [
            'message' => $message,
            'data' => $user,
            'token' => $user->api_token
        ];
        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     description="Authenticate user and get API token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@admin.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="api_token", type="string", example="1|abc123token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login failed")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {      
        $credentials = $request->only('email', 'password');

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success = $user;
            $success['api_token'] = $user->createToken('auth_token')->plainTextToken;
            return response()->json([ 'message' => __('message.login_success'),'token' => $success['api_token'], 'data' => $success ], 200 );
        }
        else{
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401); // MUST be 401
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if($request->is('api*'))
        {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => __('message.logout_success')]);
        }
    }
}
