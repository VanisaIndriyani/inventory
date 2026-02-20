<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\StockIn;
use Illuminate\Database\Seeder;

class StockInSeeder extends Seeder
{
    public function run(): void
    {
        $inventories = Inventory::query()->get();

        if ($inventories->isEmpty()) {
            return;
        }

        $data = [
            [
                'code' => 'CON-001',
                'rows' => [
                    ['received_at' => now()->subDays(10)->toDateString(), 'quantity' => 200, 'supplier' => 'PT Sehat Selalu', 'received_by' => 'Petugas Gudang 1'],
                    ['received_at' => now()->subDays(3)->toDateString(), 'quantity' => 150, 'supplier' => 'PT Sehat Selalu', 'received_by' => 'Petugas Gudang 2'],
                ],
            ],
            [
                'code' => 'CON-002',
                'rows' => [
                    ['received_at' => now()->subDays(7)->toDateString(), 'quantity' => 120, 'supplier' => 'PT Bersih Bersama', 'received_by' => 'Petugas Gudang 1'],
                ],
            ],
            [
                'code' => 'CON-003',
                'rows' => [
                    ['received_at' => now()->subDays(5)->toDateString(), 'quantity' => 300, 'supplier' => 'PT Sentosa Medika', 'received_by' => 'Petugas Gudang 3'],
                ],
            ],
            [
                'code' => 'CON-004',
                'rows' => [
                    ['received_at' => now()->subDays(2)->toDateString(), 'quantity' => 500, 'supplier' => 'PT Office Supply', 'received_by' => 'Petugas Gudang 2'],
                ],
            ],
        ];

        foreach ($data as $item) {
            $inventory = $inventories->firstWhere('code', $item['code']);

            if (!$inventory) {
                continue;
            }

            foreach ($item['rows'] as $row) {
                StockIn::query()->create([
                    'inventory_id' => $inventory->id,
                    'received_at' => $row['received_at'],
                    'quantity' => $row['quantity'],
                    'supplier' => $row['supplier'],
                    'received_by' => $row['received_by'],
                ]);
            }
        }
    }
}

