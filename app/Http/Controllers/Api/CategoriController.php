<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categori;
use Illuminate\Http\Request;

class CategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Categori::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'titre' => 'required',
        ]);
        $existing = Categori::where('titre', $request->titre)->exists();
        if ($existing) {
            return response()->json(['message' => 'Catégorie déjà existante !'], 400);
        }
        $categori = Categori::create($request->all());

        return response()->json($categori, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $categori = Categori::findOrFail($id);
        return response()->json($categori, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $categori = Categori::findOrFail($id);
        $request->validate([
            'titre' => 'required',
        ]);
        $categori->update($request->all());
        return response()->json($categori, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $categori = Categori::findOrFail($id);
        $categori->delete();
        return response()->json(['message' => 'Catégorie supprimée avec succès'], 200);
    }
}
