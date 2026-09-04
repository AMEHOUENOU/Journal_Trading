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

        $trades = $account->trades()->latest('opened_at')->paginate(20);

        return view('accounts.show', [
            'account' => $account,
            'trades' => $trades,
            'winRate' => $account->winRate(),
            'profitFactor' => $account->profitFactor(),
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