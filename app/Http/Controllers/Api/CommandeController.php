<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(
            Commande::with(['etudiant', 'etudiant.user'])
                ->get(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'date' => 'nullable',
            'categorie' => 'nullable',
            'status' => 'nullable',
            'etudiant_id' => 'required|exists:etudiants,id',
        ]);

        $commande = Commande::create($validated);
        return response()->json($commande, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $commande = Commande::with(['etudiant', 'etudiant.user'])->find($id);
        return response()->json($commande);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $commande = Commande::find($id);
        $validated = $request->validate([
            'date' => 'nullable',
            'categorie' => 'nullable',
            'status' => 'nullable',
        ]);

        $commande->update($validated);
        return response()->json($commande);
    }

    public function getByIdEtudiant($etudiant_id,)
    {
        $trans = Commande::where('etudiant_id', $etudiant_id)
            ->with('etudiant')
            ->with('etudiant.user')
            ->get();
        return response()->json($trans, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $commande = Commande::find($id);
        $commande->delete();
        return response()->json(['message' => 'Commande supprimé !']);
    }
}
