<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Au;
use Illuminate\Http\Request;

class AuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Au::with('etablissement')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'annee_debut' => 'nullable|integer',
            'annee_fin' => 'nullable|integer',
            'etablissement_id' => 'required|exists:etablissements,id'
        ]);
        $existing = Au::where('annee_debut', $request->annee_debut)
            ->where('annee_fin', $request->annee_fin)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'AU déjà existante !'], 400);
        }
        $au = Au::create($request->all());
        return response()->json($au, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $au = Au::findOrFail($id);
        return response()->json($au->load('etablissement'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $au = Au::findOrFail($id);
        $request->validate([
            'annee_debut' => 'nullable',
            'annee_fin' => 'nullable',
            'etablissement_id' => 'required|exists:etablissements,id'
        ]);
        $au->update($request->all());
        return response()->json($au, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $au = Au::findOrFail($id);
        $au->delete();
        return response()->json(null, 204);
    }
}
