<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('signUp', 'signIn');
    }

    /**
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/api/v1/auth/signup",
     *   summary="Add new account",
     *
     *   @OA\RequestBody(ref="#/components/requestBodies/SignUpRequest"),
     *
     *   @OA\Response(
     *       response=201,
     *       description="Created",
     *
     *       @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/UserResource")),
     *   ),
     *
     *   @OA\Response(response=422, description="Unprocessable Content"),
     *   @OA\Response(response=403, description="Forbidden"),
     * )
     */
    public function signUp(SignUpRequest $request): UserResource
    {
        $data = $request->validated();

        $user = new User();
        $user->fill($data);
        $user->save();

        return UserResource::make($user);
    }

    /**
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/api/v1/auth/signin",
     *   summary="Retrieve access token",
     *
     *   @OA\RequestBody(ref="#/components/requestBodies/SignInRequest"),
     *
     *   @OA\Response(
     *       response=200,
     *       description="OK",
     *
     *       @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/TokenResource")),
     *   ),
     *
     *   @OA\Response(response=422, description="Unprocessable Content"),
     *   @OA\Response(response=403, description="Forbidden"),
     * )
     */
    public function signIn(SignInRequest $request): TokenResource
    {
        $login = $request->validated('login');
        $password = $request->validated('password');

        $user = User::where('email', $login)->first() ?? User::where('phone', $login)->first();
        if ($user) {
            if (Hash::check($password, $user->password)) {
                return TokenResource::make($user->createToken('auth'));
            }
        }

        throw new AuthorizationException();
    }

    /**
     * @OA\Get(
     *   tags={"Auth"},
     *   path="/api/v1/auth/getaccount",
     *   summary="Get account data",
     *
     *   @OA\Response(
     *       response=200,
     *       description="OK",
     *
     *       @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/UserResource")),
     *   ),
     *
     *   @OA\Response(response=422, description="Unprocessable Content"),
     *   @OA\Response(response=403, description="Forbidden"),
     * )
     */
    public function getUser(Request $request): UserResource
    {
        $user = $request->user();
        if ($user) {
            return UserResource::make($user);
        }

        throw new AuthorizationException();
    }
}
