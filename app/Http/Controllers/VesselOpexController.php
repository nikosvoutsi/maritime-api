<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VesselOpex;

class VesselOpexController extends Controller
{
    public function store(Request $request, $vesselId)
    {
        // Validate input
        $validatedData = $request->validate([
            'date' => 'required|date',
            'expenses' => 'required|numeric',
        ]);

        // Check if there's already an entry for the same date
        if (VesselOpex::where(['vessel_id' => $vesselId, 'date' => $validatedData['date']])->exists()) {
            return response()->json(['error' => 'Vessel already has opex entry for this date.'], 422);
        }

        // Save the opex
        $validatedData['vessel_id'] = $vesselId;
        $opex = VesselOpex::create($validatedData);

        return response()->json($opex, 201);
    }
}