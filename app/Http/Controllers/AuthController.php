<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Cliente;
use Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/login",
     *     operationId="login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="User email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="User password"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth('web')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
   
        return $this->respondWithToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/register",
     *     operationId="register",
     *     tags={"Authentication"},
     *     summary="User registration",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User information",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "email", "password"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="User name"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     description="User email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     description="User password"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Registration successful"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $existingUser = User::where('email', $request->input('email'))->first();
            if ($existingUser) {
                throw new \Exception('Email already in use');
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }



    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/logout",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     summary="User logout",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(response="200", description="Logout successful",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     operationId="refresh",
     *     tags={"Authentication"},
     *     summary="Refresh access token",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(response="200", description="Token refreshed successfully",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/auth/user-profile",
     *     operationId="getUserProfile",
     *     tags={"Users"},
     *     summary="Get a logged user",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user()
        ]);
    }
}
