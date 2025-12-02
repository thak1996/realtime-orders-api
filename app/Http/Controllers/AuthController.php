<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException as ValidateException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'device_name' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidateException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'access_token' => $user->createToken($request->input('device_name'))->plainTextToken,
        ];
    }
}
