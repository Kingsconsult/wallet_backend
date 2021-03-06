<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Collections\StatusCodes;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Wallet;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {

        $validatedData = $request->validated();


        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;


        return response()->json([
            "status" => "success",
            "message" => "Registration Successful",
            "data" => [
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "email" => $user->email,
                "token" => $accessToken,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
                "verified_at" => $user->email_verified_at
            ]
        ], StatusCodes::CREATED);
    }

    public function login(LoginRequest $request)
    {
        $loginData = $request->validated();

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'This User does not exist, check your details'], 400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
