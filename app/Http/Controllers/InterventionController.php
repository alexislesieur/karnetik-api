<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    public function index(Request $request, int $vehicleId): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);

        $interventions = $vehicle->interventions()
            ->orderBy('performed_at', 'desc')
            ->get();

        return response()->json(['interventions' => $interventions]);
    }

    public function store(Request $request, int $vehicleId): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);

        $validated = $request->validate([
            'type'            => ['required', 'string', 'max:50'],
            'label'           => ['required', 'string', 'max:255'],
            'notes'           => ['sometimes', 'nullable', 'string'],
            'performed_at'    => ['required', 'date'],
            'mileage_at'      => ['sometimes', 'nullable', 'integer', 'min:0'],
            'garage'          => ['sometimes', 'nullable', 'string', 'max:255'],
            'garage_city'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'cost'            => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'cost_parts'      => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'cost_labor'      => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'is_professional' => ['sometimes', 'boolean'],
        ]);

        $intervention = $vehicle->interventions()->create($validated);

        if (isset($validated['mileage_at']) && $validated['mileage_at'] > $vehicle->mileage) {
            $vehicle->update(['mileage' => $validated['mileage_at']]);
        }

        return response()->json([
            'message'      => 'Intervention ajoutée.',
            'intervention' => $intervention,
        ], 201);
    }

    public function show(Request $request, int $vehicleId, int $id): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);
        $intervention = $vehicle->interventions()->findOrFail($id);

        return response()->json(['intervention' => $intervention]);
    }

    public function update(Request $request, int $vehicleId, int $id): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);
        $intervention = $vehicle->interventions()->findOrFail($id);

        $validated = $request->validate([
            'type'            => ['sometimes', 'string', 'max:50'],
            'label'           => ['sometimes', 'string', 'max:255'],
            'notes'           => ['sometimes', 'nullable', 'string'],
            'performed_at'    => ['sometimes', 'date'],
            'mileage_at'      => ['sometimes', 'nullable', 'integer', 'min:0'],
            'garage'          => ['sometimes', 'nullable', 'string', 'max:255'],
            'garage_city'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'cost'            => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'cost_parts'      => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'cost_labor'      => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'is_professional' => ['sometimes', 'boolean'],
        ]);

        $intervention->update($validated);

        return response()->json([
            'message'      => 'Intervention mise à jour.',
            'intervention' => $intervention,
        ]);
    }

    public function destroy(Request $request, int $vehicleId, int $id): JsonResponse
    {
        $vehicle = $request->user()->vehicles()->findOrFail($vehicleId);
        $intervention = $vehicle->interventions()->findOrFail($id);
        $intervention->delete();

        return response()->json(['message' => 'Intervention supprimée.']);
    }
}