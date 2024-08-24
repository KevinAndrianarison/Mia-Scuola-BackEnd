<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mention;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $validatedData = $request->validate([
            'nom_mention' => 'nullable|string',
            'abr_mention' => 'nullable|string',
            'niveau_ids' => 'required|array',
            'niveau_ids.*.id' => 'exists:niveaux,id',
            'niveau_ids.*.abr' => 'required|string',
        ]);

        foreach ($validatedData['niveau_ids'] as $niveau) {
            $niveau_id = $niveau['id'];
            $abrMentionCombiné = $validatedData['abr_mention'] . '-' . $niveau['abr'];
            $existingMention = Mention::where('abr_mention', $abrMentionCombiné)
                ->where('niveau_id', $niveau_id)
                ->first();

            if ($existingMention) {
                return response()->json(['message' => 'Mention déjà existante !']);
            }
            Mention::create([
                'nom_mention' => $validatedData['nom_mention'],
                'abr_mention' => $abrMentionCombiné,
                'niveau_id' => $niveau_id,
            ]);
        }

        return response()->json(['message' => 'Mention créé !'], 201);
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

    public function getByNiveauId($niveau_id)
    {
        $niveau = Mention::where('niveau_id', $niveau_id)->get();
        return response()->json($niveau, 200);
    }
}
