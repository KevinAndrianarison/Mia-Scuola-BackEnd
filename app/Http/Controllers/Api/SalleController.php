<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Salle::get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nom_salle' => 'nullable',

        ]);

        $existing = Salle::where('nom_salle', $request->nom_salle)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'Salle déjà existant !'], 400);
        }
        $salle = Salle::create($request->all());
        return response()->json($salle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $salle = Salle::findOrFail($id);
        return response()->json($salle, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $salle = Salle::findOrFail($id);
        $request->validate([
            'nom_salle' => 'nullable',
        ]);
        $salle->update($request->all());
        return response()->json($salle, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $salle = Salle::findOrFail($id);
        $salle->delete();
        return response()->json(null, 204);
    }
}
