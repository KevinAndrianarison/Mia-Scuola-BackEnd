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
    // public function show($id)
    // {
    //     //
    //     $etudiant = Etudiant::with('ec')
    //     ->with('ec.ue')
    //     ->with('ec.au')
    //     ->with('ec.ue.semestre')
    //     ->findOrFail($id);
    //     return response()->json($etudiant->load('user'), 200);
    // }

    public function show($id)
    {
        $etudiant = Etudiant::with([
            'ec',
            'ec.ue',
            'ec.ue.semestre',
            'ec.au',
            'user'
        ])->findOrFail($id);

        $response = $this->structureSemestreAndUe($etudiant);

        return response()->json($response, 200);
    }
    private function structureSemestreAndUe($etudiant)
    {
        $semestres = [];
        $uesCreditsNotes = [];

        foreach ($etudiant->ec as $ec) {
            $semestreId = $ec->ue->semestre->id ?? null;

            if ($semestreId) {
                if (!isset($semestres[$semestreId])) {
                    $semestres[$semestreId] = [
                        'id' => $semestreId,
                        'nom_semestre' => $ec->ue->semestre->nom_semestre,
                        'ues' => []
                    ];
                }

                $ueId = $ec->ue_id;

                if (!isset($semestres[$semestreId]['ues'][$ueId])) {
                    $semestres[$semestreId]['ues'][$ueId] = [
                        'id' => $ueId,
                        'nom_ue' => $ec->ue->nom_ue,
                        'credit' => $ec->ue->credit_ue,
                        'ecs' => []
                    ];
                }

                $semestres[$semestreId]['ues'][$ueId]['ecs'][] = [
                    'id' => $ec->id,
                    'nom_ec' => $ec->nom_ec,
                    'volume_et' => $ec->volume_et,
                    'volume_ed' => $ec->volume_ed,
                    'volume_tp' => $ec->volume_tp,
                    'noteEc' => $ec->pivot->noteEc ?? null,
                    'au' => $ec->au ?? null
                ];
            }
        }
        foreach ($semestres as &$semestre) {
            foreach ($semestre['ues'] as &$ue) {
                $notesEc = array_column($ue['ecs'], 'noteEc');
                $notesValides = array_filter($notesEc, fn($note) => !is_null($note));
                $ue['moyenne_ue'] = !empty($notesValides)
                    ? round(array_sum($notesValides) / count($notesValides), 2)
                    : null;
                if (!is_null($ue['moyenne_ue'])) {
                    $uesCreditsNotes[] = [
                        'note_ponderee' => $ue['moyenne_ue'] * $ue['credit'],
                        'credit' => $ue['credit']
                    ];
                }
            }

            $semestre['ues'] = array_values($semestre['ues']);
        }
        $totalCredits = array_sum(array_column($uesCreditsNotes, 'credit'));
        $totalNotesPonderees = array_sum(array_column($uesCreditsNotes, 'note_ponderee'));

        $moyenneGenerale = $totalCredits > 0 ? round($totalNotesPonderees / $totalCredits, 2) : null;

        return [
            'etudiant' => $etudiant,
            'user' => $etudiant->user,
            'semestres' => array_values($semestres),
            'moyenne_generale' => $moyenneGenerale
        ];
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
