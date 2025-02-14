<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Niveau::with('au')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nom_niveau' => 'nullable',
            'abr_niveau' => 'nullable',
            'montant_droit' => 'nullable',
            'au_id' => 'required|exists:aus,id'

        ]);

        $existing = Niveau::where('abr_niveau', $request->abr_niveau)
            ->where('au_id', $request->au_id)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'Niveau déjà existant !'], 400);
        }
        $niveau = Niveau::create($request->all());
        return response()->json($niveau, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $niveau = Niveau::findOrFail($id);
        return response()->json($niveau->load('au'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $niveau = Niveau::findOrFail($id);
        $request->validate([
            'nom_niveau' => 'nullable',
            'abr_niveau' => 'nullable',
            'montant_droit' => 'nullable',
        ]);
        $niveau->update($request->all());
        return response()->json($niveau, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $niveau = Niveau::findOrFail($id);
        $niveau->delete();
        return response()->json(null, 204);
    }

    public function getByAuId($au_id)
    {
        $niveau = Niveau::where('au_id', $au_id)->get();
        return response()->json($niveau, 200);
    }
}
