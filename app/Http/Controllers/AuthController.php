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

    public function signUp(SignUpRequest $request): UserResource
    {
        $data = $request->validated();

        $user = new User();
        $user->fill($data);
        $user->save();

        return UserResource::make($user);
    }

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

    public function getUser(Request $request): UserResource
    {
        $user = $request->user();
        if ($user) {
            return UserResource::make($user);
        }

        throw new AuthorizationException();
    }
}
