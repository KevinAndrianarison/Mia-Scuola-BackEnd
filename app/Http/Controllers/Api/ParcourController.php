<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parcour;
use Illuminate\Http\Request;

class ParcourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Parcour::with('mention')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_parcours' => 'nullable',
            'abr_parcours' => 'nullable',
            'mention_id' => 'required|exists:mentions,id',
            'enseignant_id' => 'nullable|exists:enseignants,id',
            'niveau_id' => 'required|exists:niveaux,id'
        ]);

        $existing = Parcour::where('abr_parcours', $request->abr_parcours)
            ->where('mention_id', $request->mention_id)
            ->where('niveau_id', $request->niveau_id)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'Parcours déjà existant !']);
        }
        $parcours = Parcour::create($request->all());
        return response()->json($parcours, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $parcours = Parcour::findOrFail($id);
        return response()->json($parcours->load('mention'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $parcours = Parcour::findOrFail($id);
        $request->validate([
            'nom_parcours' => 'nullable',
            'abr_parcours' => 'nullable',
            'enseignant_id' => 'nullable|exists:enseignants,id',

        ]);
        if ($parcours->enseignant_id) {
            $request->request->remove('enseignant_id');
            return response()->json(['message' => 'Un enseignant est déjà associé à ce parcours !']);
        }
        $parcours->update($request->all());
        return response()->json($parcours, 200);
    }



    public function clearEnseignantId($id)
    {
        $parcours = Parcour::findOrFail($id);
        $parcours->enseignant_id = null;
        $parcours->save();
        return response()->json(['message' => 'L\'enseignant a été dissocié avec succès !'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $parcours = Parcour::findOrFail($id);
        $parcours->delete();
        return response()->json(null, 204);
    }

    public function getByNiveauId($niveau_id)
    {
        $parcours = Parcour::where('niveau_id', $niveau_id)
            ->with('enseignant')
            ->get();
        return response()->json($parcours, 200);
    }

    public function getByEnseignantId($enseignant_id)
    {
        $parcours = Parcour::where('enseignant_id', $enseignant_id)->get();
        return response()->json($parcours, 200);
    }
}
