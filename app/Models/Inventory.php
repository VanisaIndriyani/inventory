<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $fillable = [
        'code',
        'name',
        'initial_stock',
        'main_supplier',
        'supplier_lead_time',
        'storage_location',
        'safety_stock',
    ];

    protected $casts = [
        'initial_stock' => 'integer',
        'supplier_lead_time' => 'integer',
        'safety_stock' => 'integer',
    ];

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
        return $this->initial_stock + $this->total_in - $this->total_out;
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
        $leadTimeDays = max($this->supplier_lead_time, 0);
        $averageDailyUsage = $this->average_daily_usage_30;

        $demandDuringLeadTime = $averageDailyUsage * $leadTimeDays;

        return (int) round(max(0, $safetyStock + $demandDuringLeadTime));
    }

    public function getStatusAttribute(): string
    {
        $finalStock = $this->final_stock;
        $safetyStock = max($this->safety_stock, 1);
        $reorderPoint = $this->reorder_point;

        if ($finalStock <= $reorderPoint) {
            return 'Reorder';
        }

        if ($finalStock <= $reorderPoint + (int) round($safetyStock * 0.5)) {
            return 'Warning';
        }

        return 'Aman';
    }
}
