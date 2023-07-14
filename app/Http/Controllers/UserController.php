<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserController extends Controller 
{
    /**
     * @OA\PathItem(
     *     path="/api/users",
     *     @OA\Get(
     *         operationId="getUsersList",
     *         tags={"Users"},
     *         summary="Get a list of users",
     *         security={{"bearerAuth":{}}}, 
     *         @OA\Response(
     *             response="200",
     *             description="Successful operation",
     *             @OA\MediaType(
     *                 mediaType="application/json"
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/users/{id}",
     *     operationId="getUser",
     *     tags={"User"},
     *     summary="Get user by ID",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found",
     *     )
     * )
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

}
