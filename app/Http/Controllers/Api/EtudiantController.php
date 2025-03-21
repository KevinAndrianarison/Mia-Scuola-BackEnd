<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        // Validation des entrées
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
            'status_etud' => 'nullable',
            'validiter_inscri' => 'nullable',
            'nom_tuteur' => 'nullable',
            'user_id' => 'required|exists:users,id',
            'au_id' => 'required|exists:aus,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'cursu_id' => 'required|exists:cursus,id'

        ]);
        $exists = Etudiant::where('nomComplet_etud', $request->nomComplet_etud)
            ->where('au_id', $request->au_id)
            ->exists();
        if ($exists) {
            $this->destroyUser($request->user_id);
            return response()->json(['message' => "Une erreur s'est produite !"], 409);
        }
        $etudiant = Etudiant::create($request->all());
        return response()->json($etudiant, 201);
    }


    public function destroyUser($id)
    {
        $fileRecord = User::find($id);

        if ($fileRecord) {
            if ($fileRecord->photo_name) {
                Storage::disk('public')->delete('users/' . $fileRecord->photo_name);
            }

            $fileRecord->delete();
        }
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
            'user',
            'niveau'
        ])->findOrFail($id);

        $response = $this->structureSemestreAndUe($etudiant);

        return response()->json($response, 200);
    }

    public function getEtudiantByCursusId($cursu_id)
    {
        $etudiants = Etudiant::where('cursu_id', $cursu_id)
            ->with([
                'ec',
                'ec.ue',
                'ec.ue.semestre',
                'ec.au',
                'user',
                'niveau'
            ])->get();
        $response = [];
        foreach ($etudiants as $etudiant) {
            $response[] = $this->structureSemestreAndUe($etudiant);
        }
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
                        'edt' => $ec->ue->semestre->groupedt,
                        'cours' => $ec->cour,
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
            'niveau' => $etudiant->niveau,
            'au' => $etudiant->au,
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
            'status_etud' => 'nullable',
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



    // -------------------------------------------------------------------------------------------------------------------------------------------
    public function updateByEmailAndPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'validiter_compte' => 'required',
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
            'status_etud' => 'nullable',
            'validiter_inscri' => 'nullable',
            'nom_tuteur' => 'nullable',
            'photoBordereaux' => 'nullable',
        ]);
        $user = User::where('email', $validatedData['email'])
            ->where('validiter_compte', $validatedData['validiter_compte'])
            ->first();
        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['error' => 'Utilisateur non trouvé ou mot de passe incorrect'], 404);
        }
        $etudiant = Etudiant::where('user_id', $user->id)->first();

        if (!$etudiant) {
            return response()->json(['error' => 'Étudiant non trouvé'], 404);
        }
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


    // ---------------------------------------------------------------------------------------------------------------------------------------------

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

    public function getByAuId($au_id)
    {
        $etudiant = Etudiant::where('au_id', $au_id)
            ->with('au')
            ->with('user')
            ->with('niveau')
            ->with('semestre')
            ->with('semestre.parcour')
            ->get();
        return response()->json($etudiant, 200);
    }
}
