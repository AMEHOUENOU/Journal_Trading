<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'description', 'entry_rules', 'exit_rules'];

    public function trades(): BelongsToMany
    {
        return $this->belongsToMany(Trade::class, 'tag_trade');
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