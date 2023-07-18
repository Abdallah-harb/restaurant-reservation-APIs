<?php

namespace App\Http\Controllers\Api\Public\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserAuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $token = auth('api')->attempt(["email" => $request->email, "password" => $request->password]);
        $user->token = $token;
        return ResponseHelper::sendResponseSuccess(new UserAuthResource($user));
    }
    public function login(LoginRequest $request)
    {
        if (!$token = auth('api')->attempt(["email" => $request->email, "password" => $request->password])) {
            return ResponseHelper::sendResponseError([], Response::HTTP_BAD_REQUEST, __("messages.The email or password is incorrect"));
        }
        $user = User::whereEmail($request->email)->first();
        $user->token = $token;
        return ResponseHelper::sendResponseSuccess(new UserAuthResource($user));
    }
}
