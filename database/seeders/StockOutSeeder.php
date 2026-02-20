<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\StockOut;
use Illuminate\Database\Seeder;

class StockOutSeeder extends Seeder
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
                    ['issued_at' => now()->subDays(8)->toDateString(), 'quantity' => 120, 'department' => 'Operasional', 'purpose' => 'Pemakaian harian'],
                    ['issued_at' => now()->subDays(1)->toDateString(), 'quantity' => 80, 'department' => 'Kesehatan Kerja', 'purpose' => 'Stok unit'],
                ],
            ],
            [
                'code' => 'CON-002',
                'rows' => [
                    ['issued_at' => now()->subDays(4)->toDateString(), 'quantity' => 60, 'department' => 'Operasional', 'purpose' => 'Pemakaian kantor'],
                ],
            ],
            [
                'code' => 'CON-003',
                'rows' => [
                    ['issued_at' => now()->subDays(3)->toDateString(), 'quantity' => 150, 'department' => 'Kesehatan Kerja', 'purpose' => 'Stok klinik'],
                ],
            ],
            [
                'code' => 'CON-004',
                'rows' => [
                    ['issued_at' => now()->subDays(2)->toDateString(), 'quantity' => 200, 'department' => 'Administrasi', 'purpose' => 'Pengadaan dokumen'],
                ],
            ],
            [
                'code' => 'CON-005',
                'rows' => [
                    ['issued_at' => now()->subDays(6)->toDateString(), 'quantity' => 30, 'department' => 'Operasional', 'purpose' => 'Penggantian tinta'],
                ],
            ],
        ];

        foreach ($data as $item) {
            $inventory = $inventories->firstWhere('code', $item['code']);

            if (!$inventory) {
                continue;
            }

            foreach ($item['rows'] as $row) {
                StockOut::query()->create([
                    'inventory_id' => $inventory->id,
                    'issued_at' => $row['issued_at'],
                    'quantity' => $row['quantity'],
                    'department' => $row['department'],
                    'purpose' => $row['purpose'],
                ]);
            }
        }
    }
}

