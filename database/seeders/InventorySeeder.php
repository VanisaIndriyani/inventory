<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $monthColumn = Inventory::stockColumnForMonth((int) Carbon::now()->month);

        $items = [
            [
                'code' => 'CON-001',
                'name' => 'Masker Medis 3 Ply',
                'storage_location' => 'Gudang A - Rak 01',
                'safety_stock' => 150,
                'usage_rate' => 30,
                'lead_time' => 3,
                $monthColumn => 500,
            ],
            [
                'code' => 'CON-002',
                'name' => 'Hand Sanitizer 500ml',
                'storage_location' => 'Gudang A - Rak 02',
                'safety_stock' => 100,
                'usage_rate' => 12,
                'lead_time' => 5,
                $monthColumn => 300,
            ],
            [
                'code' => 'CON-003',
                'name' => 'Sarung Tangan Latex',
                'storage_location' => 'Gudang B - Rak 01',
                'safety_stock' => 200,
                'usage_rate' => 45,
                'lead_time' => 7,
                $monthColumn => 800,
            ],
            [
                'code' => 'CON-004',
                'name' => 'Kertas A4 80gsm',
                'storage_location' => 'Gudang C - Rak 01',
                'safety_stock' => 250,
                'usage_rate' => 60,
                'lead_time' => 4,
                $monthColumn => 1000,
            ],
            [
                'code' => 'CON-005',
                'name' => 'Tinta Printer Hitam',
                'storage_location' => 'Gudang C - Rak 03',
                'safety_stock' => 40,
                'usage_rate' => 2,
                'lead_time' => 6,
                $monthColumn => 120,
            ],
        ];

        foreach ($items as $item) {
            $item = array_merge([
                'stock_jan' => 0,
                'stock_feb' => 0,
                'stock_mar' => 0,
                'stock_apr' => 0,
                'stock_may' => 0,
                'stock_jun' => 0,
                'stock_jul' => 0,
                'stock_aug' => 0,
                'stock_sep' => 0,
                'stock_oct' => 0,
                'stock_nov' => 0,
                'stock_dec' => 0,
                'day_1' => 0,
                'day_2' => 0,
                'day_3' => 0,
                'day_4' => 0,
                'day_5' => 0,
                'day_6' => 0,
                'day_7' => 0,
                'day_8' => 0,
                'day_9' => 0,
                'day_10' => 0,
                'day_11' => 0,
                'day_12' => 0,
                'day_13' => 0,
                'day_14' => 0,
                'day_15' => 0,
                'day_16' => 0,
                'day_17' => 0,
                'day_18' => 0,
                'day_19' => 0,
                'day_20' => 0,
                'day_21' => 0,
                'day_22' => 0,
                'day_23' => 0,
                'day_24' => 0,
                'day_25' => 0,
                'day_26' => 0,
                'day_27' => 0,
                'day_28' => 0,
                'day_29' => 0,
                'day_30' => 0,
            ], $item);

            Inventory::query()->updateOrCreate(
                ['code' => $item['code']],
                $item,
            );
        }
    }
}
