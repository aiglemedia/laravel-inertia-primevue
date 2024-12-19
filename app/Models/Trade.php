<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    /** @use HasFactory<\Database\Factories\TradeFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
}
