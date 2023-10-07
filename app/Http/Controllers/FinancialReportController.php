<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    public function getReport($vesselId)
    {
        // Use SQL queries to retrieve the required data
        $report = DB::table('voyages')
    ->select(
        'voyages.id as voyage_id',
        'voyages.start',
        'voyages.end',
        'voyages.revenues',
        'voyages.expenses',
        'voyages.profit',
        DB::raw('(voyages.profit / EXTRACT(EPOCH FROM (voyages.end - voyages.start)) / 86400) as voyage_profit_daily_average'),
        DB::raw('(SELECT SUM(expenses) FROM vessel_opex WHERE vessel_id = ? AND date BETWEEN voyages.start AND voyages.end) as vessel_expenses_total'),
        DB::raw('(voyages.profit - (SELECT SUM(expenses) FROM vessel_opex WHERE vessel_id = ? AND date BETWEEN voyages.start AND voyages.end)) as net_profit'),
        DB::raw('((voyages.profit - (SELECT SUM(expenses) FROM vessel_opex WHERE vessel_id = ? AND date BETWEEN voyages.start AND voyages.end)) / EXTRACT(EPOCH FROM (voyages.end - voyages.start)) / 86400) as net_profit_daily_average')
    )
    ->where('voyages.vessel_id', $vesselId)
    ->addBinding($vesselId, 'select')
    ->addBinding($vesselId, 'select')
    ->addBinding($vesselId, 'select')
    ->get();

return response()->json($report);
    }
}
