<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mention;
use Illuminate\Http\Request;

class MentionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Mention::with('niveau')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'nom_mention' => 'nullable',
            'abr_mention' => 'nullable',
            'niveau_ids' => 'required|array',
            'niveau_ids.*' => 'exists:niveaux,id',
        ]);

        foreach ($validatedData['niveau_ids'] as $niveau_id) {
            Mention::create([
                'nom_mention' => $validatedData['nom_mention'],
                'abr_mention' => $validatedData['abr_mention'],
                'niveau_id' => $niveau_id,
            ]);
        }
        return response()->json(['message' => 'Les mentions ont été ajoutées avec succès.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $mention = Mention::findOrFail($id);
        return response()->json($mention->load('niveau'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $mention = Mention::findOrFail($id);
        $request->validate([
            'nom_mention' => 'nullable',
            'abr_mention' => 'nullable',
            'niveau_id' => 'required|exists:niveaux,id'
        ]);
        $mention->update($request->all());
        return response()->json($mention, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $mention = Mention::findOrFail($id);
        $mention->delete();
        return response()->json(null, 204);
    }
}
