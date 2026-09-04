<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TradeController extends Controller
{
    public function index(Account $account)
    {
        $this->authorizeAccount($account);

        $trades = $account->trades()
            ->when(request('symbol'), fn($q) => $q->where('symbol', 'like', '%' . request('symbol') . '%'))
            ->when(request('close_status'), fn($q) => $q->where('close_status', request('close_status')))
            ->latest('opened_at')
            ->paginate(20);

        return view('trades.index', compact('account', 'trades'));
    }

    public function create(Account $account)
    {
        $this->authorizeAccount($account);
        $tags = auth()->user()->tags;
        return view('trades.create', compact('account', 'tags'));
    }

    public function store(Request $request, Account $account)
    {
        $this->authorizeAccount($account);

        $validated = $this->validateTrade($request);

        if ($request->hasFile('screenshot')) {
            $validated['screenshot_path'] = $request->file('screenshot')->store('screenshots', 'public');
        }

        $trade = $account->trades()->create($validated);

        if ($request->filled('tags')) {
            $trade->tags()->sync($request->input('tags'));
        }

        $this->recalculateAccountBalance($account);

        return redirect()->route('accounts.show', $account)->with('success', 'Trade enregistré.');
    }

   

    public function edit(Trade $trade)
    {
        $this->authorizeTrade($trade);
        $tags = auth()->user()->tags;
        return view('trades.edit', ['trade' => $trade, 'account' => $trade->account, 'tags' => $tags]);
    }

    public function update(Request $request, Trade $trade)
    {
        $this->authorizeTrade($trade);

        $validated = $this->validateTrade($request);

        if ($request->hasFile('screenshot')) {
            if ($trade->screenshot_path) {
                Storage::disk('public')->delete($trade->screenshot_path);
            }
            $validated['screenshot_path'] = $request->file('screenshot')->store('screenshots', 'public');
        }

        $trade->update($validated);
        $trade->tags()->sync($request->input('tags', []));

        $this->recalculateAccountBalance($trade->account);

        return redirect()->route('accounts.show', $trade->account)->with('success', 'Trade mis à jour.');
    }

    public function destroy(Trade $trade)
    {
        $this->authorizeTrade($trade);
        $account = $trade->account;

        if ($trade->screenshot_path) {
            Storage::disk('public')->delete($trade->screenshot_path);
        }
        $trade->delete();

        $this->recalculateAccountBalance($account);

        return redirect()->route('accounts.show', $account)->with('success', 'Trade supprimé.');
    }

    public function export(Account $account)
    {
        $this->authorizeAccount($account);

        $trades = $account->trades()->get();
        $filename = "trades-{$account->name}-" . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($trades) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Symbol', 'Direction', 'Entry', 'Exit', 'SL', 'TP', 'Size', 'PnL', 'R:R', 'Status', 'Opened', 'Closed', 'Notes']);

            foreach ($trades as $trade) {
                fputcsv($handle, [
                    $trade->symbol, $trade->direction, $trade->entry_price, $trade->exit_price,
                    $trade->stop_loss, $trade->take_profit, $trade->position_size, $trade->pnl,
                    $trade->risk_reward, $trade->close_status, $trade->opened_at, $trade->closed_at, $trade->notes,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request, Account $account)
    {
        $this->authorizeAccount($account);

        $request->validate(['file' => 'required|file|mimes:csv,txt']);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $account->trades()->create([
                'symbol' => $data['Symbol'] ?? $data['symbol'] ?? '',
                'direction' => strtolower($data['Direction'] ?? $data['direction'] ?? 'long'),
                'entry_price' => $data['Entry'] ?? $data['entry_price'] ?? 0,
                'exit_price' => $data['Exit'] ?? $data['exit_price'] ?? null,
                'stop_loss' => $data['SL'] ?? $data['stop_loss'] ?? null,
                'take_profit' => $data['TP'] ?? $data['take_profit'] ?? null,
                'position_size' => $data['Size'] ?? $data['position_size'] ?? 0,
                'pnl' => $data['PnL'] ?? $data['pnl'] ?? null,
                'close_status' => $data['Status'] ?? $data['close_status'] ?? 'manual',
                'opened_at' => $data['Opened'] ?? $data['opened_at'] ?? now(),
                'closed_at' => $data['Closed'] ?? $data['closed_at'] ?? null,
                'notes' => $data['Notes'] ?? $data['notes'] ?? null,
            ]);
        }
        fclose($handle);

        $this->recalculateAccountBalance($account);

        return redirect()->route('accounts.show', $account)->with('success', 'Import terminé.');
    }

    private function validateTrade(Request $request): array
    {
        return $request->validate([
            'symbol' => 'required|string|max:50',
            'direction' => 'required|in:long,short',
            'entry_price' => 'required|numeric',
            'exit_price' => 'nullable|numeric',
            'stop_loss' => 'nullable|numeric',
            'take_profit' => 'nullable|numeric',
            'position_size' => 'required|numeric|min:0',
            'pnl' => 'nullable|numeric',
            'close_status' => 'required|in:sl_hit,tp_hit,manual,breakeven,open',
            'opened_at' => 'required|date',
            'closed_at' => 'nullable|date',
            'notes' => 'nullable|string',
            'screenshot' => 'nullable|image|max:5120',
        ]);
    }

    private function recalculateAccountBalance(Account $account): void
    {
        $totalPnl = $account->trades()->whereNotNull('pnl')->sum('pnl');
        $account->update(['current_balance' => $account->initial_balance + $totalPnl]);
    }

    private function authorizeAccount(Account $account): void
    {
        abort_if($account->user_id !== auth()->id(), 403);
    }

    private function authorizeTrade(Trade $trade): void
    {
        abort_if($trade->account->user_id !== auth()->id(), 403);
    }

    public function show(Trade $trade)
{
    $this->authorizeTrade($trade);
    $trade->load('tags', 'photos', 'account');
    return view('trades.show', compact('trade'));
}
}