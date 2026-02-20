<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOut extends Model
{
    protected $table = 'stock_outs';

    protected $fillable = [
        'inventory_id',
        'issued_at',
        'department',
        'quantity',
        'purpose',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'quantity' => 'integer',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}

