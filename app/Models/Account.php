<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = ['name', 'broker', 'initial_balance', 'current_balance', 'currency'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    public function winRate(): float
    {
        $total = $this->trades()->whereNotNull('pnl')->count();
        $wins = $this->trades()->where('pnl', '>', 0)->count();
        return $total ? round($wins / $total * 100, 2) : 0;
    }

    public function profitFactor(): ?float
    {
        $grossProfit = $this->trades()->where('pnl', '>', 0)->sum('pnl');
        $grossLoss = abs($this->trades()->where('pnl', '<', 0)->sum('pnl'));
        return $grossLoss > 0 ? round($grossProfit / $grossLoss, 2) : null;
    }
}