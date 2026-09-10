<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\StrategyInstrument;
use Illuminate\Http\Request;

class StrategyInstrumentController extends Controller
{
    public function store(Request $request, Tag $tag)
    {
        $this->authorizeTag($tag);

        $validated = $this->validateInstrument($request);
        $tag->instruments()->create($validated);

        return redirect()->route('tags.show', $tag)->with('success', 'Actif ajouté à la stratégie.');
    }

    public function update(Request $request, Tag $tag, StrategyInstrument $instrument)
    {
        $this->authorizeTag($tag);
        abort_if($instrument->tag_id !== $tag->id, 403);

        $validated = $this->validateInstrument($request);
        $instrument->update($validated);

        return redirect()->route('tags.show', $tag)->with('success', 'Actif mis à jour.');
    }

    public function destroy(Tag $tag, StrategyInstrument $instrument)
    {
        $this->authorizeTag($tag);
        abort_if($instrument->tag_id !== $tag->id, 403);

        $instrument->delete();

        return redirect()->route('tags.show', $tag)->with('success', 'Actif supprimé.');
    }

    private function validateInstrument(Request $request): array
    {
        return $request->validate([
            'symbol' => 'required|string|max:50',
            'timeframe' => 'required|string|max:20',
            'bias' => 'required|in:achat,vente,neutre',
            'target_rr' => 'required|numeric',
            'breakeven_type' => 'nullable|string|max:50',
            'active_days' => 'nullable|string|max:100',
            'inactive_months' => 'nullable|string|max:100',
            'backtest_rr_percent' => 'nullable|numeric',
            'win_rate' => 'nullable|numeric',
            'drawdown' => 'nullable|numeric',
        ]);
    }

    private function authorizeTag(Tag $tag): void
    {
        abort_if($tag->user_id !== auth()->id(), 403);
    }
}
