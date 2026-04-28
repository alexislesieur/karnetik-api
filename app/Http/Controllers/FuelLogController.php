<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FuelLogController extends Controller
{
    public function index(Request $request, int $vehicleId): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);

        $logs = $vehicle->fuelLogs()
            ->orderBy('filled_at', 'desc')
            ->get();

        return response()->json(['fuel_logs' => $logs]);
    }

    public function store(Request $request, int $vehicleId): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);

        $validated = $request->validate([
            'filled_at'       => ['required', 'date'],
            'mileage_at'      => ['required', 'integer', 'min:0'],
            'liters'          => ['required', 'numeric', 'min:0'],
            'cost'            => ['required', 'numeric', 'min:0'],
            'price_per_liter' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'station'         => ['sometimes', 'nullable', 'string', 'max:255'],
            'is_full_tank'    => ['sometimes', 'boolean'],
            'notes'           => ['sometimes', 'nullable', 'string'],
        ]);

        if (!isset($validated['price_per_liter']) && $validated['liters'] > 0) {
            $validated['price_per_liter'] = round($validated['cost'] / $validated['liters'], 3);
        }

        $log = $vehicle->fuelLogs()->create($validated);

        if ($validated['mileage_at'] > $vehicle->mileage) {
            $vehicle->update(['mileage' => $validated['mileage_at']]);
        }

        return response()->json([
            'message'  => 'Plein enregistré.',
            'fuel_log' => $log,
        ], 201);
    }

    public function show(Request $request, int $vehicleId, int $id): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);
        $log = $vehicle->fuelLogs()->findOrFail($id);

        return response()->json(['fuel_log' => $log]);
    }

    public function update(Request $request, int $vehicleId, int $id): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);
        $log = $vehicle->fuelLogs()->findOrFail($id);

        $validated = $request->validate([
            'filled_at'       => ['sometimes', 'date'],
            'mileage_at'      => ['sometimes', 'integer', 'min:0'],
            'liters'          => ['sometimes', 'numeric', 'min:0'],
            'cost'            => ['sometimes', 'numeric', 'min:0'],
            'price_per_liter' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'station'         => ['sometimes', 'nullable', 'string', 'max:255'],
            'is_full_tank'    => ['sometimes', 'boolean'],
            'notes'           => ['sometimes', 'nullable', 'string'],
        ]);

        $log->update($validated);

        return response()->json([
            'message'  => 'Plein mis à jour.',
            'fuel_log' => $log,
        ]);
    }

    public function destroy(Request $request, int $vehicleId, int $id): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);
        $log = $vehicle->fuelLogs()->findOrFail($id);
        $log->delete();

        return response()->json(['message' => 'Plein supprimé.']);
    }
}