<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->only([
                'id', 'first_name', 'last_name', 'email', 'role',
                'phone', 'avatar', 'email_verified_at', 'created_at',
            ]),
        ]);
    }
}