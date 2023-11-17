<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 * )
 */

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints related to user authentication"
 * )
 */

/**
 * @OA\Post(
 *      path="/api/login",
 *      operationId="login",
 *      tags={"Authentication"},
 *      summary="Login an existing user",
 *      description="Logs in an existing user and returns an authorization token",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="email", type="string", example="user@example.com"),
 *                  @OA\Property(property="password", type="string", example="password"),
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful login",
 *          @OA\JsonContent(
 *              @OA\Property(property="user", type="object"),
 *              @OA\Property(property="authorization", type="object",
 *                  @OA\Property(property="token", type="string"),
 *                  @OA\Property(property="type", type="string", example="bearer"),
 *              ),
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string"),
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Property(property="errors", type="object"),
 *          )
 *      ),
 * )
 */

/**
 * @OA\Post(
 *      path="/api/register",
 *      operationId="register",
 *      tags={"Authentication"},
 *      summary="Register a new user",
 *      description="Registers a new user and returns an authorization token",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="name", type="string", example="John Doe"),
 *                  @OA\Property(property="email", type="string", example="newuser@example.com"),
 *                  @OA\Property(property="password", type="string", example="newpassword"),
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful registration",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="string"),
 *              @OA\Property(property="message", type="string"),
 *              @OA\Property(property="user", type="object"),
 *              @OA\Property(property="authorization", type="object",
 *                  @OA\Property(property="token", type="string"),
 *                  @OA\Property(property="type", type="string", example="bearer"),
 *              ),
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Property(property="errors", type="object"),
 *          )
 *      ),
 * )
 */


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
 * @OA\Post(
 *      path="/api/login",
 *      operationId="login",
 *      tags={"Authentication"},
 *      summary="Login an existing user",
 *      description="Logs in an existing user and returns an authorization token",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="email", type="string", example="user@example.com"),
 *                  @OA\Property(property="password", type="string", example="password"),
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful login",
 *          @OA\JsonContent(
 *              @OA\Property(property="user", type="object"),
 *              @OA\Property(property="authorization", type="object",
 *                  @OA\Property(property="token", type="string"),
 *                  @OA\Property(property="type", type="string", example="bearer"),
 *              ),
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string"),
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Property(property="errors", type="object"),
 *          )
 *      ),
 * )
 */


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

   
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    
/**
 * @OA\Post(
 *      path="/api/v1/logout",
 *      operationId="logout",
 *      tags={"Authentication"},
 *      summary="Logout the authenticated user",
 *      description="Logs out the authenticated user",
 *      security={{"bearerAuth": {}}},
 *      @OA\Response(
 *          response=200,
 *          description="Successfully logged out",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string"),
 *          )
 *      ),
 * )
 */

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/refresh",
     *      operationId="refresh",
     *      tags={"Authentication"},
     *      summary="Refresh the authentication token",
     *      description="Refreshes the authentication token for the authenticated user",
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successfully refreshed token",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", type="object"),
     *              @OA\Property(property="authorization", type="object",
     *                  @OA\Property(property="token", type="string"),
     *                  @OA\Property(property="type", type="string", example="bearer"),
     *              ),
     *          )
     *      ),
     * )
     */

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
