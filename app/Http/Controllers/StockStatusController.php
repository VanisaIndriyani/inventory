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
        $selectedMonth = (int) $request->input('month', (int) Carbon::now()->month);

        $inventories = Inventory::query()->orderBy('name')->get();

        $inventories->each(function (Inventory $inventory) use ($selectedMonth) {
            $inventory->setAttribute('selected_stock', $inventory->stockForMonth($selectedMonth));
            $inventory->setAttribute('selected_status', $inventory->statusForMonth($selectedMonth));
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
            'selectedMonth' => $selectedMonth,
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

        for ($day = 1; $day <= 30; $day++) {
            $rules['day_' . $day] = ['required', 'integer', 'min:0'];
        }

        $data = $request->validate($rules);

        $inventory->update($data);

        return redirect()
            ->route('stock-status.index', [
                'status' => $request->input('status'),
                'month' => $request->input('month'),
            ])
            ->with('status', 'Data Reorder Point berhasil diperbarui.');
    }
}
