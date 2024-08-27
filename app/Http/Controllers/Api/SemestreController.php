<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Semestre;
use Illuminate\Http\Request;

class SemestreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Semestre::with('parcour')->get(), 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nom_semestre' => 'nullable',
            'parcour_id' => 'required|exists:parcours,id',
        ]);

        $existing = Semestre::where('nom_semestre', $request->nom_semestre)
            ->where('parcour_id', $request->parcour_id)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'Semestre déjà existante !']);
        }
        $semestres = Semestre::create($request->all());
        return response()->json($semestres, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        //
        $semestres = Semestre::findOrFail($id);
        return response()->json($semestres->load('parcour'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $semestres = Semestre::findOrFail($id);
        $request->validate([
            'nom_semestre' => 'nullable',
            'parcour_id' => 'required|exists:parcours,id'

        ]);
        $semestres->update($request->all());
        return response()->json($semestres, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        //
        $semestres = Semestre::findOrFail($id);
        $semestres->delete();
        return response()->json(null, 204);
    }

    public function getByParcoursId($parcour_id)
    {
        $parcours = Semestre::where('parcour_id', $parcour_id)->get();
        return response()->json($parcours, 200);
    }
}
