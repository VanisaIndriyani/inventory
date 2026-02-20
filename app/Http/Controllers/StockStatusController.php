<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class StockStatusController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->input('status');

        $inventories = Inventory::with(['stockIns', 'stockOuts'])->get();

        $grouped = [
            'Aman' => $inventories->where('status', 'Aman'),
            'Warning' => $inventories->where('status', 'Warning'),
            'Reorder' => $inventories->where('status', 'Reorder'),
        ];

        $visibleInventories = $inventories;

        if (in_array($statusFilter, ['Aman', 'Warning', 'Reorder'], true)) {
            $visibleInventories = $grouped[$statusFilter];
        }

        return view('stock_status.index', [
            'statusFilter' => $statusFilter,
            'inventories' => $visibleInventories,
            'grouped' => $grouped,
        ]);
    }
}

