<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parcour;
use Illuminate\Http\Request;

class ParcourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Parcour::with('parcour')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_parcours' => 'nullable',
            'abr_parcours' => 'nullable',
            'mention_id' => 'required|exists:mentions,id'

        ]);

        $existing = Parcour::where('abr_parcours', $request->abr_parcours)
            ->where('mention_id', $request->mention_id)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'Parcours déjà existant !'], 400);
        }
        $parcours = Parcour::create($request->all());
        return response()->json($parcours, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $parcours = Parcour::findOrFail($id);
        return response()->json($parcours->load('parcour'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $parcours = Parcour::findOrFail($id);
        $request->validate([
            'nom_parcours' => 'nullable',
            'abr_parcours' => 'nullable',
            'mention_id' => 'required|exists:mentions,id'
        ]);
        $parcours->update($request->all());
        return response()->json($parcours, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $parcours = Parcour::findOrFail($id);
        $parcours->delete();
        return response()->json(null, 204);
    }

    public function getByNiveauId($mention_id)
    {
        $parcours = Parcour::where('mention_id', $mention_id)->get();
        return response()->json($parcours, 200);
    }
}
