<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ue;
use Illuminate\Http\Request;

class UeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Ue::with('semestre')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nom_ue' => 'nullable',
            'credit_ue' => 'nullable',
            'semestre_id' => 'required|exists:semestres,id',
        ]);

        $existing = Ue::where('nom_ue', $request->nom_ue)
            ->where('semestre_id', $request->semestre_id)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'UE déjà existante !']);
        }
        $ue = Ue::create($request->all());
        return response()->json($ue, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $ue = Ue::findOrFail($id);
        return response()->json($ue, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $ue = Ue::findOrFail($id);
        $request->validate([
            'nom_ue' => 'nullable',
            'credit_ue' => 'nullable',
        ]);
        $ue->update($request->all());
        return response()->json($ue, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $ue = Ue::findOrFail($id);
        $ue->delete();
        return response()->json(null, 204);
    }

    public function getBySemestreId($semestre_id)
    {
        $ue = Ue::where('semestre_id', $semestre_id)->get();
        return response()->json($ue, 200);
    }
}
