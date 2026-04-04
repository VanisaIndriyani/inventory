<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Inventory;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $stockIns = StockIn::with('inventory')
            ->orderByDesc('received_at')
            ->orderByDesc('id')
            ->paginate(20);

        $inventories = Inventory::orderBy('name')->get();

        return view('stock_in.index', compact('stockIns', 'inventories'));
    }

    public function create()
    {
        $inventories = Inventory::orderBy('name')->get();

        return view('stock_in.create', compact('inventories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inventory_id' => ['required', 'exists:inventories,id'],
            'received_at' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'received_by' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            $inventory = Inventory::query()
                ->lockForUpdate()
                ->findOrFail($data['inventory_id']);

            // Tidak perlu update kolom stock_xxx lagi karena kita pakai initial_stock + in - out
            StockIn::query()->create($data);
        });

        return redirect()
            ->route('stock-in.index')
            ->with('status', 'Transaksi barang masuk berhasil dicatat.');
    }
}
