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
        'initial_stock',
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
    ];

    public static function stockColumnForMonth(int $month): string
    {
        return self::MONTH_TO_STOCK_COLUMN[$month] ?? 'stock_jan';
    }

    public function stockForMonth(int $month): int
    {
        $column = self::stockColumnForMonth($month);
        return (int) ($this->{$column} ?? 0);
    }

    public function statusForMonth(int $month): string
    {
        $stock = $this->final_stock;
        $reorderPoint = $this->reorder_point;
        $safetyStock = max($this->safety_stock, 0);

        if ($stock <= $safetyStock) {
            return 'Reorder';
        }

        // Warning muncul jika stok di bawah atau sama dengan Reorder Point.
        // Jika ROP belum terhitung (masih sama dengan Safety Stock), 
        // kita gunakan threshold 50% di atas Safety Stock agar lebih sensitif.
        $warningThreshold = ($reorderPoint <= $safetyStock) 
            ? (int)round($safetyStock * 1.5) 
            : $reorderPoint;

        if ($stock <= $warningThreshold) {
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
        return (int) (
            ($this->stock_jan ?? 0) +
            ($this->stock_feb ?? 0) +
            ($this->stock_mar ?? 0) +
            ($this->stock_apr ?? 0) +
            ($this->stock_may ?? 0) +
            ($this->stock_jun ?? 0) +
            ($this->stock_jul ?? 0) +
            ($this->stock_aug ?? 0) +
            ($this->stock_sep ?? 0) +
            ($this->stock_oct ?? 0) +
            ($this->stock_nov ?? 0) +
            ($this->stock_dec ?? 0)
        );
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
