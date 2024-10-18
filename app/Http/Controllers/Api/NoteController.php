<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Note::with('etudiant')
            ->with('ec')
            ->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'note' => 'nullable',
            'etudiant_id' => 'required|exists:etudiants,id',
            'ec_id' => 'required|exists:ecs,id',
        ]);

        $note = Note::where('etudiant_id', $request->etudiant_id)
            ->where('ec_id', $request->ec_id)
            ->first();

        if ($note) {
            $note->update($request->only('note'));
            return response()->json($note);
        } else {
            $note = Note::create($request->all());
            return response()->json($note);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $note = Note::findOrFail($id);
        return response()->json($note, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $note = Note::findOrFail($id);
        $request->validate([
            'note' => 'nullable',
        ]);
        $note->update($request->all());
        return response()->json($note, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
