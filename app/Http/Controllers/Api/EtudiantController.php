<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'lieux_naissance_etud' => 'nullable',
            'nationalite_etud' => 'nullable',
            'serieBAC_etud' => 'nullable',
            'anneeBAC_etud' => 'nullable',
            'etabOrigin_etud' => 'nullable',
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
        $validatedData = $request->validate([
            'nomComplet_etud' => 'nullable',
            'date_naissance_etud' => 'nullable',
            'lieux_naissance_etud' => 'nullable',
            'nationalite_etud' => 'nullable',
            'serieBAC_etud' => 'nullable',
            'anneeBAC_etud' => 'nullable',
            'etabOrigin_etud' => 'nullable',
            'adresse_etud' => 'nullable',
            'telephone_etud' => 'nullable',
            'matricule_etud' => 'nullable',
            'nom_mere_etud' => 'nullable',
            'nom_pere_etud' => 'nullable',
            'sexe_etud' => 'nullable',
            'CIN_etud' => 'nullable',
            'validiter_inscri' => 'nullable',
            'nom_tuteur' => 'nullable',
            'photoBordereaux' => 'nullable',

        ]);
        $etudiant = Etudiant::findOrFail($id);
        if ($request->hasFile('photoBordereaux')) {

            if ($etudiant->photoBordereaux_name) {
                Storage::delete('public/bordereaux/' . $etudiant->photoBordereaux_name);
            }
            $file = $request->file('photoBordereaux');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('public/bordereaux', $fileName);
            $validatedData['photoBordereaux_name'] = $fileName;
        }
        $etudiant->update($validatedData);
        return response()->json($etudiant, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $etudiant = Etudiant::findOrFail($id);
        if ($etudiant) {
            if ($etudiant->photoBordereaux_name) {
                Storage::disk('public')->delete('bordereaux/' . $etudiant->photoBordereaux_name);
            }

            $etudiant->delete();
            return response()->json(['message' => 'Etudiant supprimé !'], 200);
        } else {
            return response()->json(['error' => 'Etudiant introuvable !'], 404);
        }
    }


    public function getByUserId($user_id)
    {
        $etudiant = Etudiant::where('user_id', $user_id)->with('user')->get();
        return response()->json($etudiant, 200);
    }
}
