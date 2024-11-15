<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ec;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class EcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Ec::with('ue')
            ->with('user')
            ->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nom_ec' => 'nullable',
            'volume_et' => 'nullable',
            'volume_ed' => 'nullable',
            'volume_tp' => 'nullable',
            'ue_id' => 'required|exists:ues,id',
            'au_id' => 'required|exists:aus,id',
            'etudiant' => 'required|array'
        ]);

        $existing = Ec::where('nom_ec', $request->nom_ec)
            ->where('ue_id', $request->ue_id)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'EC déjà existante !']);
        }
        $ec = Ec::create($request->all());
        foreach ($request->etudiant as $etudiantId) {
            $etudiant = Etudiant::findOrFail($etudiantId);
            $etudiant->ec()->attach($ec->id, ['noteEc' => null]);
        }
        return response()->json($ec, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ec = Ec::with('etudiant')->findOrFail($id);
        $response = [
            'id' => $ec->id,
            'nom_ec' => $ec->nom_ec,
            'volume_et' => $ec->volume_et,
            'volume_ed' => $ec->volume_ed,
            'volume_tp' => $ec->volume_tp,
            'etudiants' => $ec->etudiant->map(function ($etd) {
                return [
                    'id' => $etd->id,
                    'nomComplet_etud' => $etd->nomComplet_etud,
                    'user_id' => $etd->user_id,
                    'noteEc' => $etd->pivot->noteEc
                ];
            })
        ];

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $ec = Ec::findOrFail($id);
        $request->validate([
            'nom_ec' => 'nullable',
            'volume_et' => 'nullable',
            'volume_ed' => 'nullable',
            'volume_tp' => 'nullable',
            'enseignant_id' => 'nullable|exists:enseignants,id',

        ]);
        if ($ec->enseignant_id) {
            $request->request->remove('enseignant_id');
            return response()->json(['message' => 'Un enseignant est déjà associé à ce EC !']);
        }
        $ec->update($request->all());
        return response()->json($ec, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $ec = Ec::findOrFail($id);
        $ec->delete();
        return response()->json(null, 204);
    }

    public function getByUeId($ue_id)
    {
        $ec = Ec::where('ue_id', $ue_id)->with('enseignant')->with('enseignant.user')->get();
        return response()->json($ec, 200);
    }



    public function getBySemestre($semestre_id)
    {
        $ecs = Ec::whereHas('ue.semestre', function ($query) use ($semestre_id) {
            $query->where('id', $semestre_id);
        })->with(['enseignant.user'])->get();
        return response()->json($ecs, 200);
    }



    public function getByEnsegnantId($enseignant_id)
    {
        $ec = Ec::where('enseignant_id', $enseignant_id)->get();
        return response()->json($ec, 200);
    }

    public function getByEnsegnantIdAndAU($enseignant_id, $au_id)
    {
        $ec = Ec::where('enseignant_id', $enseignant_id)
            ->where('au_id', $au_id)
            ->get();
        return response()->json($ec, 200);
    }

    public function clearEnseignantId($id)
    {
        $ec = Ec::findOrFail($id);
        $ec->enseignant_id = null;
        $ec->save();
        return response()->json(['message' => 'L\'enseignant a été dissocié avec succès !'], 200);
    }

    public function updateNote(Request $request, $ecId, $etudiantId)
    {
        $request->validate([
            'noteEc' => 'nullable',
        ]);
        $ec = Ec::findOrFail($ecId);
        $etudiant = $ec->etudiant()->find($etudiantId);

        if (!$etudiant) {
            return response()->json(['message' => 'Etudiant non associé à cet EC'], 404);
        }
        $ec->etudiant()->updateExistingPivot($etudiantId, ['noteEc' => $request->noteEc]);
        return response()->json(['message' => 'Note mise à jour avec succès']);
    }
}
