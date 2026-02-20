<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with(['stockIns', 'stockOuts'])->orderBy('name')->get();

        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:inventories,code'],
            'name' => ['required', 'string', 'max:255'],
            'initial_stock' => ['required', 'integer', 'min:0'],
            'main_supplier' => ['nullable', 'string', 'max:255'],
            'supplier_lead_time' => ['required', 'integer', 'min:0'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'safety_stock' => ['required', 'integer', 'min:0'],
        ]);

        Inventory::create($data);

        return redirect()
            ->route('inventories.index')
            ->with('status', 'Data inventory berhasil ditambahkan.');
    }

    public function edit(Inventory $inventory)
    {
        return view('inventories.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:inventories,code,' . $inventory->id],
            'name' => ['required', 'string', 'max:255'],
            'initial_stock' => ['required', 'integer', 'min:0'],
            'main_supplier' => ['nullable', 'string', 'max:255'],
            'supplier_lead_time' => ['required', 'integer', 'min:0'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'safety_stock' => ['required', 'integer', 'min:0'],
        ]);

        $inventory->update($data);

        return redirect()
            ->route('inventories.index')
            ->with('status', 'Data inventory berhasil diperbarui.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()
            ->route('inventories.index')
            ->with('status', 'Data inventory berhasil dihapus.');
    }
}

