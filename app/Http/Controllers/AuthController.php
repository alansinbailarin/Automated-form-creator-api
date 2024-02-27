<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:20|regex:/^[a-zA-Z\s]+$/u',
            'surname' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|min:8'
        ];

        $validator = \Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json((
                [
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ]
            ), 400);
        }

        $randomNumbers = $this->generateRandomNumbers(6);

        $username = strtolower($request->name) . strtolower($request->surname) . $randomNumbers;
        $firstNameAndLastName = str_replace(' ', '-', strtolower($request->name)) . '-' . str_replace(' ', '-', strtolower($request->surname));
        $slug = $firstNameAndLastName . '-' . $randomNumbers;

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'username' => $username,
            'slug' => $slug,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'token' => $user->createToken('authToken')->plainTextToken,
            'data' => $user,

        ], 201);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string'
        ];

        $validator = \Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json((
                [
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ]
            ), 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'token' => $user->createToken('authToken')->plainTextToken,
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function generateRandomNumbers($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
