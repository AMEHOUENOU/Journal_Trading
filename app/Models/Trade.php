<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trade extends Model
{
    protected $fillable = [
        'symbol', 'direction', 'entry_price', 'exit_price',
        'stop_loss', 'take_profit', 'position_size', 'pnl',
        'risk_reward', 'close_status', 'opened_at', 'closed_at',
        'notes', 'screenshot_path',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_trade');
    }

    protected static function booted(): void
    {
        static::saving(function (Trade $trade) {
            if ($trade->stop_loss && $trade->take_profit && $trade->entry_price) {
                $risk = abs($trade->entry_price - $trade->stop_loss);
                $reward = abs($trade->take_profit - $trade->entry_price);
                $trade->risk_reward = $risk > 0 ? round($reward / $risk, 2) : null;
            }
        });
    }

    public function photos(): HasMany
{
    return $this->hasMany(TradePhoto::class);
}
}