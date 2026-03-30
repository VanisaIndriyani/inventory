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

    public function update(Request $request, Inventory $inventory)
    {
        $rules = [
            'usage_rate' => ['required', 'numeric', 'min:0'],
            'lead_time' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['required', 'integer', 'min:0'],
            'rop_alert' => ['required', 'integer', 'min:0'],
        ];

        for ($day = 1; $day <= 30; $day++) {
            $rules['day_' . $day] = ['required', 'integer', 'min:0'];
        }

        $data = $request->validate($rules);

        $inventory->update($data);

        return redirect()
            ->route('stock-status.index', ['status' => $request->input('status')])
            ->with('status', 'Data Reorder Point berhasil diperbarui.');
    }
}
