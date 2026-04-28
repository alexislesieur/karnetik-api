<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use Illuminate\Http\JsonResponse;

class WaitlistController extends Controller
{
    public function index(): JsonResponse
    {
        $entries = Waitlist::orderBy('created_at', 'desc')->get();

        return response()->json([
            'total'   => $entries->count(),
            'entries' => $entries,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $entry = Waitlist::findOrFail($id);
        $entry->delete();

        return response()->json([
            'message' => 'Inscription supprimée.',
        ]);
    }

    public function export(): JsonResponse
    {
        $entries = Waitlist::orderBy('created_at', 'desc')->get(['email', 'created_at']);

        return response()->json([
            'entries' => $entries,
        ]);
    }
}