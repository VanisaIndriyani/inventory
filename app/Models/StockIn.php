<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockIn extends Model
{
    protected $table = 'stock_ins';

    protected $fillable = [
        'inventory_id',
        'received_at',
        'quantity',
        'supplier',
        'received_by',
    ];

    protected $casts = [
        'received_at' => 'date',
        'quantity' => 'integer',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}

