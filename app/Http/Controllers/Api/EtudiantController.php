<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Etudiant::with('user')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nomComplet_etud' => 'nullable',
            'date_naissance_etud' => 'nullable',
            'adresse_etud' => 'nullable',
            'telephone_etud' => 'nullable',
            'matricule_etud' => 'nullable',
            'nom_mere_etud' => 'nullable',
            'nom_pere_etud' => 'nullable',
            'sexe_etud' => 'nullable',
            'CIN_etud' => 'nullable',
            'validiter_inscri' => 'nullable',
            'nom_tuteur' => 'nullable',
            'user_id' => 'required|exists:users,id'
        ]);
        $etudiant = Etudiant::create($request->all());

        return response()->json($etudiant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $etudiant = Etudiant::findOrFail($id);
        return response()->json($etudiant->load('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $etudiant = Etudiant::findOrFail($id);
        $request->validate([
            'nomComplet_etud' => 'nullable',
            'date_naissance_etud' => 'nullable',
            'adresse_etud' => 'nullable',
            'telephone_etud' => 'nullable',
            'matricule_etud' => 'nullable',
            'nom_mere_etud' => 'nullable',
            'nom_pere_etud' => 'nullable',
            'sexe_etud' => 'nullable',
            'CIN_etud' => 'nullable',
            'validiter_inscri' => 'nullable',
            'nom_tuteur' => 'nullable',
        ]);
        $etudiant->update($request->all());
        return response()->json($etudiant, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $etudiant = Etudiant::findOrFail($id);
        $etudiant->delete();
        return response()->json(null, 204);
    }
}
