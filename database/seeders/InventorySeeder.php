<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'code' => 'CON-001',
                'name' => 'Masker Medis 3 Ply',
                'initial_stock' => 500,
                'main_supplier' => 'PT Sehat Selalu',
                'supplier_lead_time' => 3,
                'storage_location' => 'Gudang A - Rak 01',
                'safety_stock' => 150,
            ],
            [
                'code' => 'CON-002',
                'name' => 'Hand Sanitizer 500ml',
                'initial_stock' => 300,
                'main_supplier' => 'PT Bersih Bersama',
                'supplier_lead_time' => 5,
                'storage_location' => 'Gudang A - Rak 02',
                'safety_stock' => 100,
            ],
            [
                'code' => 'CON-003',
                'name' => 'Sarung Tangan Latex',
                'initial_stock' => 800,
                'main_supplier' => 'PT Sentosa Medika',
                'supplier_lead_time' => 7,
                'storage_location' => 'Gudang B - Rak 01',
                'safety_stock' => 200,
            ],
            [
                'code' => 'CON-004',
                'name' => 'Kertas A4 80gsm',
                'initial_stock' => 1000,
                'main_supplier' => 'PT Office Supply',
                'supplier_lead_time' => 4,
                'storage_location' => 'Gudang C - Rak 01',
                'safety_stock' => 250,
            ],
            [
                'code' => 'CON-005',
                'name' => 'Tinta Printer Hitam',
                'initial_stock' => 120,
                'main_supplier' => 'PT Print Jaya',
                'supplier_lead_time' => 6,
                'storage_location' => 'Gudang C - Rak 03',
                'safety_stock' => 40,
            ],
        ];

        foreach ($items as $item) {
            Inventory::query()->updateOrCreate(
                ['code' => $item['code']],
                $item,
            );
        }
    }
}

