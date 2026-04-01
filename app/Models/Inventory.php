<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    private const MONTH_TO_STOCK_COLUMN = [
        1 => 'stock_jan',
        2 => 'stock_feb',
        3 => 'stock_mar',
        4 => 'stock_apr',
        5 => 'stock_may',
        6 => 'stock_jun',
        7 => 'stock_jul',
        8 => 'stock_aug',
        9 => 'stock_sep',
        10 => 'stock_oct',
        11 => 'stock_nov',
        12 => 'stock_dec',
    ];

    protected $fillable = [
        'code',
        'name',
        'main_supplier',
        'supplier_lead_time',
        'storage_location',
        'safety_stock',
        'usage_rate',
        'lead_time',
        'stock_jan',
        'stock_feb',
        'stock_mar',
        'stock_apr',
        'stock_may',
        'stock_jun',
        'stock_jul',
        'stock_aug',
        'stock_sep',
        'stock_oct',
        'stock_nov',
        'stock_dec',
        'day_1',
        'day_2',
        'day_3',
        'day_4',
        'day_5',
        'day_6',
        'day_7',
        'day_8',
        'day_9',
        'day_10',
        'day_11',
        'day_12',
        'day_13',
        'day_14',
        'day_15',
        'day_16',
        'day_17',
        'day_18',
        'day_19',
        'day_20',
        'day_21',
        'day_22',
        'day_23',
        'day_24',
        'day_25',
        'day_26',
        'day_27',
        'day_28',
        'day_29',
        'day_30',
    ];

    protected $casts = [
        'initial_stock' => 'integer',
        'supplier_lead_time' => 'integer',
        'safety_stock' => 'integer',
        'usage_rate' => 'decimal:2',
        'lead_time' => 'integer',
        'stock_jan' => 'integer',
        'stock_feb' => 'integer',
        'stock_mar' => 'integer',
        'stock_apr' => 'integer',
        'stock_may' => 'integer',
        'stock_jun' => 'integer',
        'stock_jul' => 'integer',
        'stock_aug' => 'integer',
        'stock_sep' => 'integer',
        'stock_oct' => 'integer',
        'stock_nov' => 'integer',
        'stock_dec' => 'integer',
        'day_1' => 'integer',
        'day_2' => 'integer',
        'day_3' => 'integer',
        'day_4' => 'integer',
        'day_5' => 'integer',
        'day_6' => 'integer',
        'day_7' => 'integer',
        'day_8' => 'integer',
        'day_9' => 'integer',
        'day_10' => 'integer',
        'day_11' => 'integer',
        'day_12' => 'integer',
        'day_13' => 'integer',
        'day_14' => 'integer',
        'day_15' => 'integer',
        'day_16' => 'integer',
        'day_17' => 'integer',
        'day_18' => 'integer',
        'day_19' => 'integer',
        'day_20' => 'integer',
        'day_21' => 'integer',
        'day_22' => 'integer',
        'day_23' => 'integer',
        'day_24' => 'integer',
        'day_25' => 'integer',
        'day_26' => 'integer',
        'day_27' => 'integer',
        'day_28' => 'integer',
        'day_29' => 'integer',
        'day_30' => 'integer',
    ];

    public static function stockColumnForMonth(int $month): string
    {
        return self::MONTH_TO_STOCK_COLUMN[$month] ?? 'stock_jan';
    }

    public function stockForMonth(int $month): int
    {
        $column = self::stockColumnForMonth($month);
        $value = (int) ($this->{$column} ?? 0);

        if ($value > 0) {
            return $value;
        }

        $monthlyTotal = (int) (
            (int) ($this->stock_jan ?? 0)
            + (int) ($this->stock_feb ?? 0)
            + (int) ($this->stock_mar ?? 0)
            + (int) ($this->stock_apr ?? 0)
            + (int) ($this->stock_may ?? 0)
            + (int) ($this->stock_jun ?? 0)
            + (int) ($this->stock_jul ?? 0)
            + (int) ($this->stock_aug ?? 0)
            + (int) ($this->stock_sep ?? 0)
            + (int) ($this->stock_oct ?? 0)
            + (int) ($this->stock_nov ?? 0)
            + (int) ($this->stock_dec ?? 0)
        );

        if ($monthlyTotal === 0 && (int) ($this->initial_stock ?? 0) > 0) {
            return (int) $this->initial_stock;
        }

        return $value;
    }

    public function statusForMonth(int $month): string
    {
        $stock = $this->stockForMonth($month);
        $reorderPoint = $this->reorder_point;
        $warningPoint = $reorderPoint + max($this->safety_stock, 0);

        if ($stock <= $reorderPoint) {
            return 'Reorder';
        }

        if ($stock <= $warningPoint) {
            return 'Warning';
        }

        return 'Aman';
    }

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    public function getTotalInAttribute(): int
    {
        return $this->stockIns->sum('quantity');
    }

    public function getTotalOutAttribute(): int
    {
        return $this->stockOuts->sum('quantity');
    }

    public function getFinalStockAttribute(): int
    {
        return $this->current_stock;
    }

    public function getCurrentStockAttribute(): int
    {
        return $this->stockForMonth((int) Carbon::now()->month);
    }

    public function getAverageDailyUsage30Attribute(): float
    {
        $fromDate = Carbon::now()->subDays(29)->startOfDay();

        $totalOutLast30 = $this->stockOuts()
            ->whereDate('issued_at', '>=', $fromDate->toDateString())
            ->sum('quantity');

        return $totalOutLast30 / 30;
    }

    public function getReorderPointAttribute(): int
    {
        $safetyStock = max($this->safety_stock, 0);
        $leadTimeDays = max($this->lead_time, 0);
        $usageRate = max((float) $this->usage_rate, 0);

        return (int) round(max(0, ($usageRate * $leadTimeDays) + $safetyStock));
    }

    public function getStatusAttribute(): string
    {
        return $this->statusForMonth((int) Carbon::now()->month);
    }
}
