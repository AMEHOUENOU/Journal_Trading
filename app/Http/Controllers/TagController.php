<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = auth()->user()->tags()->withCount('trades')->get();
        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'entry_rules' => 'nullable|string',
            'exit_rules' => 'nullable|string',
        ]);

        $tag = auth()->user()->tags()->create($validated);

        return redirect()->route('tags.show', $tag)->with('success', 'Stratégie créée.');
    }

    public function show(Tag $tag)
    {
        $this->authorizeTag($tag);
        $trades = $tag->trades()->latest('opened_at')->paginate(15);

        return view('tags.show', [
            'tag' => $tag,
            'trades' => $trades,
            'winRate' => $tag->winRate(),
            'profitFactor' => $tag->profitFactor(),
        ]);
    }

    public function edit(Tag $tag)
    {
        $this->authorizeTag($tag);
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorizeTag($tag);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'entry_rules' => 'nullable|string',
            'exit_rules' => 'nullable|string',
        ]);

        $tag->update($validated);

        return redirect()->route('tags.show', $tag)->with('success', 'Stratégie mise à jour.');
    }

    public function destroy(Tag $tag)
    {
        $this->authorizeTag($tag);
        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Stratégie supprimée.');
    }

    private function authorizeTag(Tag $tag): void
    {
        abort_if($tag->user_id !== auth()->id(), 403);
    }
}