<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);

        $user = User::create([
            'first_name' => '',
            'last_name'  => '',
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'Compte créé. Vérifiez votre email pour activer votre compte.',
            'user'    => $user->only(['id', 'email']),
        ], 201);
    }
}