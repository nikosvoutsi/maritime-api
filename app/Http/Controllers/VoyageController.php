<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voyage;

class VoyageController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'vessel_id' => 'required|exists:vessels,id',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
            'revenues' => 'nullable|numeric',
            'expenses' => 'nullable|numeric',
        ]);

        // Auto-generate voyage code
        $vessel = \App\Models\Vessel::findOrFail($validatedData['vessel_id']);
        $code = $vessel->name . '-' . now()->format('Y-m-d');

        // Set status to 'pending'
        $validatedData['status'] = 'pending';
        $validatedData['code'] = $code;

        // Save the voyage
        $voyage = Voyage::create($validatedData);

        return response()->json($voyage, 201);
    }

    public function update(Request $request, $voyageId)
    {
        // Validate input
        $validatedData = $request->validate([
            'start' => 'date',
            'end' => 'nullable|date|after:start',
            'revenues' => 'nullable|numeric',
            'expenses' => 'nullable|numeric',
            'status' => 'in:pending,ongoing,submitted',
        ]);

        // Check if the voyage can be edited (e.g., not submitted)
        $voyage = Voyage::findOrFail($voyageId);
        if ($voyage->status === 'submitted') {
            return response()->json(['error' => 'Cannot edit a submitted voyage.'], 422);
        }

        // Calculate profit
        $profit = $validatedData['revenues'] - $validatedData['expenses'];
        $validatedData['profit'] = $profit;

        // Update the voyage
        $voyage->update($validatedData);

        return response()->json($voyage);
    }
}
