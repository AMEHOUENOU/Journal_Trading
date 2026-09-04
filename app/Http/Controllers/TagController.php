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
        $validated = $request->validate(['name' => 'required|string|max:100']);
        auth()->user()->tags()->create($validated);

        return redirect()->route('tags.index')->with('success', 'Tag créé.');
    }

    public function edit(Tag $tag)
    {
        $this->authorizeTag($tag);
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorizeTag($tag);
        $validated = $request->validate(['name' => 'required|string|max:100']);
        $tag->update($validated);

        return redirect()->route('tags.index')->with('success', 'Tag mis à jour.');
    }

    public function destroy(Tag $tag)
    {
        $this->authorizeTag($tag);
        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Tag supprimé.');
    }

    private function authorizeTag(Tag $tag): void
    {
        abort_if($tag->user_id !== auth()->id(), 403);
    }
}