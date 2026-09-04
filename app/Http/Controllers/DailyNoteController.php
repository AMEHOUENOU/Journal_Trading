<?php

namespace App\Http\Controllers;

use App\Models\DailyNote;
use Illuminate\Http\Request;

class DailyNoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->dailyNotes()->orderByDesc('date')->paginate(15);
        return view('daily-notes.index', compact('notes'));
    }

    public function create()
    {
        return view('daily-notes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'nullable|string',
        ]);

        auth()->user()->dailyNotes()->create($validated);

        return redirect()->route('daily-notes.index')->with('success', 'Note enregistrée.');
    }

    public function edit(DailyNote $dailyNote)
    {
        $this->authorizeNote($dailyNote);
        return view('daily-notes.edit', ['note' => $dailyNote]);
    }

    public function update(Request $request, DailyNote $dailyNote)
    {
        $this->authorizeNote($dailyNote);

        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'nullable|string',
        ]);

        $dailyNote->update($validated);

        return redirect()->route('daily-notes.index')->with('success', 'Note mise à jour.');
    }

    public function destroy(DailyNote $dailyNote)
    {
        $this->authorizeNote($dailyNote);
        $dailyNote->delete();

        return redirect()->route('daily-notes.index')->with('success', 'Note supprimée.');
    }

    private function authorizeNote(DailyNote $note): void
    {
        abort_if($note->user_id !== auth()->id(), 403);
    }
}