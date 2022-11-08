<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'username' => ['required', 'min:3', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:12'],
        ]);

        $user = User::create($attributes);

        $token = $user->createToken('usertoken')->plainTextToken;

        $response = [
            'user' => [
                "name" => $user->name,
                "username" => $user->username,
            ],
            'token' => $token
        ];

        return Response($response, Response::HTTP_CREATED);
    }

    public function login(Request $request) 
    {
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'max:12'],
        ]);

        $user = User::firstWhere('email', $attributes['email']);

        if (!$user) {
            return Response([
                'message' => 'User does not exist'
            ], Response::HTTP_NOT_FOUND);
        } 
        
        $isPasswordCorrect = Hash::check($attributes['password'], $user->password);

        if(!$isPasswordCorrect) {
            return Response([
                'message' => 'Incorrect credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('usertoken')->plainTextToken;

        $response = [
            'user' => [
                "name" => $user->name,
                "username" => $user->username,
            ],
            'token' => $token
        ];

        return Response($response, Response::HTTP_OK);
    }

    public function logout(Request $request) 
    {
        $request->user()->currentAccessToken()->delete();

        $response = [
            'message' => 'Logged out',
        ];

        return Response($response, Response::HTTP_OK);
    }
}