<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockOut;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $inventories = Inventory::with(['stockIns', 'stockOuts'])->orderBy('name')->get();

        $report = $inventories->map(function (Inventory $inventory) use ($fromDate, $toDate) {
            $stockIns = $inventory->stockIns;
            $stockOuts = $inventory->stockOuts;

            if ($fromDate) {
                $stockIns = $stockIns->where('received_at', '>=', $fromDate);
                $stockOuts = $stockOuts->where('issued_at', '>=', $fromDate);
            }

            if ($toDate) {
                $stockIns = $stockIns->where('received_at', '<=', $toDate);
                $stockOuts = $stockOuts->where('issued_at', '<=', $toDate);
            }

            $totalIn = (int) $stockIns->sum('quantity');
            $totalOut = (int) $stockOuts->sum('quantity');
            $finalStock = $inventory->initial_stock + $totalIn - $totalOut;

            return [
                'inventory' => $inventory,
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'final_stock' => $finalStock,
                'status' => $inventory->status,
            ];
        });

        $sortedByConsumption = $report->sortByDesc('total_out')->values();

        $supplierAggregates = StockIn::query()
            ->when($fromDate, function ($query) use ($fromDate) {
                $query->whereDate('received_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                $query->whereDate('received_at', '<=', $toDate);
            })
            ->with('inventory')
            ->get()
            ->groupBy(function (StockIn $stockIn) {
                if ($stockIn->supplier) {
                    return $stockIn->supplier;
                }

                if ($stockIn->inventory && $stockIn->inventory->main_supplier) {
                    return $stockIn->inventory->main_supplier;
                }

                return 'Tidak ditentukan';
            })
            ->map(function ($group, $supplierName) use ($report) {
                $totalQuantity = (int) $group->sum('quantity');

                $inventoryIds = $group
                    ->pluck('inventory_id')
                    ->filter()
                    ->unique()
                    ->values();

                $itemsCount = $inventoryIds->count();

                $criticalItems = $report->filter(function (array $row) use ($inventoryIds) {
                    if (! $inventoryIds->contains($row['inventory']->id)) {
                        return false;
                    }

                    return in_array($row['status'], ['Warning', 'Reorder'], true);
                })->count();

                return [
                    'supplier' => $supplierName,
                    'total_quantity' => $totalQuantity,
                    'items_count' => $itemsCount,
                    'critical_items' => $criticalItems,
                ];
            })
            ->sortByDesc('total_quantity')
            ->values();

        return view('reports.index', [
            'report' => $report,
            'consumptionReport' => $sortedByConsumption,
            'supplierReport' => $supplierAggregates,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $inventories = Inventory::with(['stockIns', 'stockOuts'])->orderBy('name')->get();

        $report = $inventories->map(function (Inventory $inventory) use ($fromDate, $toDate) {
            $stockIns = $inventory->stockIns;
            $stockOuts = $inventory->stockOuts;

            if ($fromDate) {
                $stockIns = $stockIns->where('received_at', '>=', $fromDate);
                $stockOuts = $stockOuts->where('issued_at', '>=', $fromDate);
            }

            if ($toDate) {
                $stockIns = $stockIns->where('received_at', '<=', $toDate);
                $stockOuts = $stockOuts->where('issued_at', '<=', $toDate);
            }

            $totalIn = (int) $stockIns->sum('quantity');
            $totalOut = (int) $stockOuts->sum('quantity');
            $finalStock = $inventory->initial_stock + $totalIn - $totalOut;

            return [
                'inventory' => $inventory,
                'total_in' => $totalIn,
                'total_out' => $totalOut,
                'final_stock' => $finalStock,
                'status' => $inventory->status,
            ];
        });

        $supplierAggregates = StockIn::query()
            ->when($fromDate, function ($query) use ($fromDate) {
                $query->whereDate('received_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                $query->whereDate('received_at', '<=', $toDate);
            })
            ->with('inventory')
            ->get()
            ->groupBy(function (StockIn $stockIn) {
                if ($stockIn->supplier) {
                    return $stockIn->supplier;
                }

                if ($stockIn->inventory && $stockIn->inventory->main_supplier) {
                    return $stockIn->inventory->main_supplier;
                }

                return 'Tidak ditentukan';
            })
            ->map(function ($group, $supplierName) use ($report) {
                $totalQuantity = (int) $group->sum('quantity');

                $inventoryIds = $group
                    ->pluck('inventory_id')
                    ->filter()
                    ->unique()
                    ->values();

                $itemsCount = $inventoryIds->count();

                $criticalItems = $report->filter(function (array $row) use ($inventoryIds) {
                    if (! $inventoryIds->contains($row['inventory']->id)) {
                        return false;
                    }

                    return in_array($row['status'], ['Warning', 'Reorder'], true);
                })->count();

                return [
                    'supplier' => $supplierName,
                    'total_quantity' => $totalQuantity,
                    'items_count' => $itemsCount,
                    'critical_items' => $criticalItems,
                ];
            })
            ->sortByDesc('total_quantity')
            ->values();

        $pdf = Pdf::loadView('reports.pdf', [
            'report' => $report,
            'supplierReport' => $supplierAggregates,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ])->setPaper('a4', 'landscape');

        $fileName = 'laporan-stok-' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}
