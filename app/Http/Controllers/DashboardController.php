<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockOut;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $inventories = Inventory::with(['stockIns', 'stockOuts'])->get();

        $totalItems = $inventories->count();
        $totalStock = $inventories->sum(fn (Inventory $inventory) => $inventory->final_stock);

        $amanCount = $inventories->where('status', 'Aman')->count();
        $warningCount = $inventories->where('status', 'Warning')->count();
        $reorderCount = $inventories->where('status', 'Reorder')->count();

        $labels = [];
        $incomingData = [];
        $outgoingData = [];

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if (!$fromDate && !$toDate) {
            $toDate = Carbon::now()->toDateString();
            $fromDate = Carbon::now()->subDays(6)->toDateString();
        } elseif ($fromDate && !$toDate) {
            $toDate = Carbon::now()->toDateString();
        } elseif (!$fromDate && $toDate) {
            $fromDate = Carbon::parse($toDate)->subDays(6)->toDateString();
        }

        $stockInQuery = StockIn::query();
        $stockOutQuery = StockOut::query();

        if ($fromDate && $toDate) {
            $stockInQuery->whereBetween('received_at', [$fromDate, $toDate]);
            $stockOutQuery->whereBetween('issued_at', [$fromDate, $toDate]);
        }

        $stockInByDate = $stockInQuery
            ->selectRaw('DATE(received_at) as date, SUM(quantity) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $stockOutByDate = $stockOutQuery
            ->selectRaw('DATE(issued_at) as date, SUM(quantity) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        if ($fromDate && $toDate) {
            $period = CarbonPeriod::create($fromDate, $toDate);

            foreach ($period as $date) {
                $formatted = $date->format('Y-m-d');
                $labels[] = $formatted;
                $incomingData[] = (int) ($stockInByDate[$formatted] ?? 0);
                $outgoingData[] = (int) ($stockOutByDate[$formatted] ?? 0);
            }
        }

        $criticalItems = $inventories->filter(fn (Inventory $inventory) => $inventory->status !== 'Aman');

        return view('dashboard.index', [
            'inventories' => $inventories,
            'totalItems' => $totalItems,
            'totalStock' => $totalStock,
            'amanCount' => $amanCount,
            'warningCount' => $warningCount,
            'reorderCount' => $reorderCount,
            'criticalItems' => $criticalItems,
            'pieLabels' => ['Reorder', 'Warning', 'Aman'],
            'pieData' => [$reorderCount, $warningCount, $amanCount],
            'chartLabels' => $labels,
            'chartIncoming' => $incomingData,
            'chartOutgoing' => $outgoingData,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }
}
