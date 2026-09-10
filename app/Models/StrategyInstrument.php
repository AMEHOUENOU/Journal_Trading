<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategyInstrument extends Model
{
    protected $fillable = [
        'symbol', 'timeframe', 'bias', 'target_rr', 'breakeven_type',
        'active_days', 'inactive_months', 'backtest_rr_percent', 'win_rate', 'drawdown',
    ];

    private const DAY_MAP = [
        'lun' => 'Lundi', 'mar' => 'Mardi', 'mer' => 'Mercredi', 'jeu' => 'Jeudi',
        'ven' => 'Vendredi', 'sam' => 'Samedi', 'dim' => 'Dimanche',
    ];

    private const MONTH_MAP = [
        'jan' => 'Janvier', 'fev' => 'Février', 'mars' => 'Mars', 'avr' => 'Avril',
        'mai' => 'Mai', 'juin' => 'Juin', 'juil' => 'Juillet', 'aou' => 'Août',
        'sep' => 'Septembre', 'oct' => 'Octobre', 'nov' => 'Novembre', 'dec' => 'Décembre',
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    private static function normalize(string $text): string
    {
        return str_replace(['é', 'û', 'â', 'è'], ['e', 'u', 'a', 'e'], mb_strtolower(trim($text)));
    }

    public function activeDaysList(): array
    {
        $text = self::normalize($this->active_days ?? '');
        if ($text === '') return [];
        if (str_contains($text, 'toute la semaine')) {
            return array_values(self::DAY_MAP);
        }

        $days = [];
        foreach (preg_split('/[-,\/]+/', $text) as $part) {
            $key = mb_substr(trim($part), 0, 3);
            if (isset(self::DAY_MAP[$key])) {
                $days[] = self::DAY_MAP[$key];
            }
        }
        return array_values(array_unique($days));
    }

    public function inactiveMonthsList(): array
    {
        $text = self::normalize($this->inactive_months ?? '');
        if ($text === '' || str_contains($text, 'aucun')) return [];

        $months = [];
        foreach (preg_split('/[,]+/', $text) as $part) {
            $part = trim($part);
            foreach (self::MONTH_MAP as $abbr => $full) {
                if (str_starts_with($part, $abbr)) {
                    $months[] = $full;
                    break;
                }
            }
        }
        return array_values(array_unique($months));
    }

    public function activeMonthsList(): array
    {
        return array_values(array_diff(self::monthNames(), $this->inactiveMonthsList()));
    }

    public static function monthNames(): array
    {
        return ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    }

    public static function dayNames(): array
    {
        return ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    }
}
