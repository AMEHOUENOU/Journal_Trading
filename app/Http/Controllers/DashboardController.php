<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $accounts = auth()->user()->accounts;

        $allTrades = \App\Models\Trade::whereIn('account_id', $accounts->pluck('id'))
            ->whereNotNull('pnl')
            ->orderBy('closed_at')
            ->get();

        $totalTrades = $allTrades->count();
        $wins = $allTrades->where('pnl', '>', 0)->count();
        $winRate = $totalTrades ? round($wins / $totalTrades * 100, 2) : 0;

        $grossProfit = $allTrades->where('pnl', '>', 0)->sum('pnl');
        $grossLoss = abs($allTrades->where('pnl', '<', 0)->sum('pnl'));
        $profitFactor = $grossLoss > 0 ? round($grossProfit / $grossLoss, 2) : null;

        $avgRR = round($allTrades->whereNotNull('risk_reward')->avg('risk_reward'), 2);

        // Equity curve cumulative
        $equityCurve = [];
        $cumulative = 0;
        foreach ($allTrades as $trade) {
            $cumulative += $trade->pnl;
            $equityCurve[] = [
                'date' => $trade->closed_at?->format('Y-m-d'),
                'balance' => $cumulative,
            ];
        }

        return view('dashboard', compact(
            'accounts', 'totalTrades', 'winRate', 'profitFactor', 'avgRR', 'equityCurve'
        ));
    }
}