<?php

use App\Http\Controllers\VoyageController;
use App\Http\Controllers\VesselOpexController;
use App\Http\Controllers\FinancialReportController;

// Test routes for Voyages
Route::post('/voyages', [VoyageController::class, 'store']);
Route::put('/voyages/{voyageId}', [VoyageController::class, 'update']);

// Test routes for Vessel Opex
Route::post('/vessels/{vesselId}/vessel-opex', [VesselOpexController::class, 'store']);

// Test route for Financial Report
Route::get('/vessels/{vesselId}/financial-report', [FinancialReportController::class, 'getReport']);

