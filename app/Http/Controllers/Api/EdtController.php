<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Edt;
use Illuminate\Http\Request;

class EdtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $edts = Edt::with(['jour', 'heure', 'enseignant', 'salle', 'ec', "groupedt"])->get();
        return response()->json($edts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'jour_id' => 'required|exists:jours,id',
            'heure_id' => 'required|exists:heures,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'salle_id' => 'required|exists:salles,id',
            'ec_id' => 'required|exists:ecs,id',
            'groupedt_id' => 'required|exists:groupedts,id',
        ]);

        $edt = Edt::create($validated);
        return response()->json($edt, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $edt = Edt::with(['jour', 'heure', 'enseignant', 'salle', 'ec', "groupedt"])->find($id);
        return response()->json($edt);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $edt = Edt::find($id);
        $validated = $request->validate([
            'jour_id' => 'exists:jours,id',
            'heure_id' => 'exists:heures,id',
            'enseignant_id' => 'exists:enseignants,id',
            'salle_id' => 'exists:salles,id',
            'ec_id' => 'exists:ecs,id',
            'groupedt_id' => 'exists:groupedts,id',

        ]);

        $edt->update($validated);
        return response()->json($edt);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $edt = Edt::find($id);
        $edt->delete();
        return response()->json(['message' => 'Emploi du temps supprimé avec succès !']);
    }
}
