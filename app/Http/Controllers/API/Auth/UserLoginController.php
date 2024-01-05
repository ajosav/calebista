<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserLoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function __invoke(UserLoginRequest $request)
    {
        $user = $request->getAuthenticable();
        $token = $user->createToken($request->email)->plainTextToken;
        $user->last_login = now();
        $user->save();
        $user->token = $token;
        return response()->success('Login successful', new UserResource($user));
    }
}
