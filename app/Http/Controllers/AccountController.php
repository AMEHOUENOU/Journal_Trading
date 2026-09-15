<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = auth()->user()->accounts()->latest()->get();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'broker' => 'nullable|string|max:255',
            'initial_balance' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
        ]);

        $validated['current_balance'] = $validated['initial_balance'];

        auth()->user()->accounts()->create($validated);

        return redirect()->route('accounts.index')->with('success', 'Compte créé avec succès.');
    }

    public function show(Account $account)
{
    $this->authorizeAccount($account);

    $trades = $account->trades()
        ->when(request('symbol'), fn($q) => $q->where('symbol', 'like', '%' . request('symbol') . '%'))
        ->when(request('close_status'), fn($q) => $q->where('close_status', request('close_status')))
        ->when(request('direction'), fn($q) => $q->where('direction', request('direction')))
        ->when(request('date_from'), fn($q) => $q->whereDate('opened_at', '>=', request('date_from')))
        ->when(request('date_to'), fn($q) => $q->whereDate('opened_at', '<=', request('date_to')))
        ->when(request('result') === 'win', fn($q) => $q->where('pnl', '>', 0))
        ->when(request('result') === 'loss', fn($q) => $q->where('pnl', '<', 0))
        ->when(request('tag_id'), fn($q) => $q->whereHas('tags', fn($tq) => $tq->where('tags.id', request('tag_id'))))
        ->latest('opened_at')
        ->paginate(20)
        ->withQueryString();

    $allTrades = $account->trades;

    $statusCounts = [
        'sl_hit' => $allTrades->where('close_status', 'sl_hit')->count(),
        'tp_hit' => $allTrades->where('close_status', 'tp_hit')->count(),
        'manual' => $allTrades->where('close_status', 'manual')->count(),
        'breakeven' => $allTrades->where('close_status', 'breakeven')->count(),
        'open' => $allTrades->where('close_status', 'open')->count(),
    ];

    $resultCounts = [
        'win' => $allTrades->where('pnl', '>', 0)->count(),
        'loss' => $allTrades->where('pnl', '<', 0)->count(),
        'breakeven_pnl' => $allTrades->where('pnl', 0)->count(),
    ];

    return view('accounts.show', [
        'account' => $account,
        'trades' => $trades,
        'winRate' => $account->winRate(),
        'profitFactor' => $account->profitFactor(),
        'tags' => auth()->user()->tags,
        'statusCounts' => $statusCounts,
        'resultCounts' => $resultCounts,
        'totalTradesCount' => $allTrades->count(),
    ]);
}

    public function edit(Account $account)
    {
        $this->authorizeAccount($account);
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $this->authorizeAccount($account);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'broker' => 'nullable|string|max:255',
            'current_balance' => 'required|numeric',
            'currency' => 'required|string|max:10',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index')->with('success', 'Compte mis à jour.');
    }

    public function destroy(Account $account)
    {
        $this->authorizeAccount($account);
        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Compte supprimé.');
    }

    private function authorizeAccount(Account $account): void
    {
        abort_if($account->user_id !== auth()->id(), 403);
    }
}