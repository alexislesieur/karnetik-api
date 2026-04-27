<?php

namespace App\Http\Controllers;

use App\Models\Waitlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $exists = Waitlist::where('email', $request->email)->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Vous êtes déjà sur la liste.',
            ]);
        }

        Waitlist::create(['email' => $request->email]);

        return response()->json([
            'message' => 'Inscription confirmée.',
        ], 201);
    }
}