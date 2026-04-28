<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $vehicles = $request->user()->vehicles()
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['vehicles' => $vehicles]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'brand'   => ['required', 'string', 'max:255'],
            'model'   => ['required', 'string', 'max:255'],
            'version' => ['sometimes', 'nullable', 'string', 'max:255'],
            'plate'   => ['sometimes', 'nullable', 'string', 'max:20'],
            'vin'     => ['sometimes', 'nullable', 'string', 'max:50'],
            'year'    => ['sometimes', 'nullable', 'integer', 'min:1900', 'max:2100'],
            'fuel'    => ['sometimes', 'nullable', 'string', 'max:50'],
            'engine'  => ['sometimes', 'nullable', 'string', 'max:100'],
            'mileage' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'color'   => ['sometimes', 'nullable', 'string', 'max:50'],
        ]);

        $vehicle = $request->user()->vehicles()->create($validated);

        return response()->json([
            'message' => 'Véhicule ajouté.',
            'vehicle' => $vehicle,
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($id);

        return response()->json(['vehicle' => $vehicle]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($id);

        $validated = $request->validate([
            'brand'   => ['sometimes', 'string', 'max:255'],
            'model'   => ['sometimes', 'string', 'max:255'],
            'version' => ['sometimes', 'nullable', 'string', 'max:255'],
            'plate'   => ['sometimes', 'nullable', 'string', 'max:20'],
            'vin'     => ['sometimes', 'nullable', 'string', 'max:50'],
            'year'    => ['sometimes', 'nullable', 'integer', 'min:1900', 'max:2100'],
            'fuel'    => ['sometimes', 'nullable', 'string', 'max:50'],
            'engine'  => ['sometimes', 'nullable', 'string', 'max:100'],
            'mileage' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'color'   => ['sometimes', 'nullable', 'string', 'max:50'],
        ]);

        $vehicle->update($validated);

        return response()->json([
            'message' => 'Véhicule mis à jour.',
            'vehicle' => $vehicle,
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($id);
        $vehicle->delete();

        return response()->json(['message' => 'Véhicule supprimé.']);
    }
}