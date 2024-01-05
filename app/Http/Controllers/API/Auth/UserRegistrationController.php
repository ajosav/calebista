<?php

namespace App\Http\Controllers\API\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserRegistrationRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRegistrationController extends Controller
{
    public function __invoke(UserRegistrationRequest $request, CreateNewUser $new_user)
    {
        $user = $new_user->create($request->validated());
        return response()->success('User Created', $user, Response::HTTP_CREATED);
    }
}
