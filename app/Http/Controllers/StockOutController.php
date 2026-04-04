<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Inventory;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        ]);

        $issuedDate = Carbon::parse($data['issued_at']);
        $monthColumn = Inventory::stockColumnForMonth((int) $issuedDate->month);

        try {
            DB::transaction(function () use ($data, $monthColumn) {
                $inventory = Inventory::query()
                    ->lockForUpdate()
                    ->findOrFail($data['inventory_id']);

                $currentStock = $inventory->final_stock;
                $quantity = (int) $data['quantity'];

                if ($quantity > $currentStock) {
                    throw new \RuntimeException('Stok tidak mencukupi.');
                }

                // Tidak perlu update kolom stock_xxx lagi karena kita pakai initial_stock + in - out
                StockOut::query()->create($data);
            });
        } catch (\RuntimeException $exception) {
            return back()
                ->withErrors(['quantity' => 'Stok bulan ini tidak mencukupi untuk pengeluaran tersebut.'])
                ->withInput();
        }

        return redirect()
            ->route('stock-out.index')
            ->with('status', 'Transaksi barang keluar berhasil dicatat.');
    }
}
