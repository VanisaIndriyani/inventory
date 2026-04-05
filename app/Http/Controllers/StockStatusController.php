<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockStatusController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->input('status');

        $inventories = Inventory::query()->orderBy('name')->get();

        $inventories->each(function (Inventory $inventory) {
            $inventory->setAttribute('selected_status', $inventory->status);
        });

        $grouped = [
            'Aman' => $inventories->where('selected_status', 'Aman'),
            'Warning' => $inventories->where('selected_status', 'Warning'),
            'Reorder' => $inventories->where('selected_status', 'Reorder'),
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

    public function update(Request $request, Inventory $inventory)
    {
        $rules = [
            'usage_rate' => ['required', 'numeric', 'min:0'],
            'lead_time' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['required', 'integer', 'min:0'],
        ];

        $data = $request->validate($rules);

        $inventory->update($data);

        return redirect()
            ->route('stock-status.index', [
                'status' => $request->input('status'),
            ])
            ->with('status', 'Data Reorder Point berhasil diperbarui.');
    }
}
