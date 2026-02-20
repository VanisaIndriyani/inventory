<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StockOut;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index()
    {
        $stockOuts = StockOut::with('inventory')
            ->orderByDesc('issued_at')
            ->orderByDesc('id')
            ->paginate(20);

        $inventories = Inventory::orderBy('name')->get();

        return view('stock_out.index', compact('stockOuts', 'inventories'));
    }

    public function create()
    {
        $inventories = Inventory::orderBy('name')->get();

        return view('stock_out.create', compact('inventories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inventory_id' => ['required', 'exists:inventories,id'],
            'issued_at' => ['required', 'date'],
            'department' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'purpose' => ['nullable', 'string', 'max:255'],
        ]);

        StockOut::create($data);

        return redirect()
            ->route('stock-out.index')
            ->with('status', 'Transaksi barang keluar berhasil dicatat.');
    }
}
