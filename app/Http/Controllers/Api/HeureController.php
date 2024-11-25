<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Heure;
use Illuminate\Http\Request;

class HeureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Heure::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'valeur' => 'required|string',
        ]);
        $existingHeure = Heure::where('valeur', $request->valeur)->exists();
        if ($existingHeure) {
            return response()->json(['message' => 'Cette heure existe déjà !']);
        };
        $heure = Heure::create([
            'valeur' => $request->valeur,
        ]);
        return response()->json([
            'message' => 'Heure ajoutée avec succès !',
            'heure' => $heure,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $heure = Heure::findOrFail($id);
        $heure->delete();
        return response()->json(null, 204);
    }
}
