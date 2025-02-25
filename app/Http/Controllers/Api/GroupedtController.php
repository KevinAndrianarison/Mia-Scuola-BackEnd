<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Groupedt;
use Illuminate\Http\Request;

class GroupedtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $group = Groupedt::with(['semestre', 'parcour', 'au', 'edt'])->get();
        return response()->json($group);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'au_id' => 'required|exists:aus,id',
            'parcour_id' => 'required|exists:parcours,id',
            'semestre_id' => 'required|exists:semestres,id',
        ]);

        $group = Groupedt::create($validated);
        return response()->json($group, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $group = Groupedt::with(['semestre', 'parcour', 'au', 'edt', 'edt.enseignant', 'edt.heure', 'edt.salle', 'edt.jour'])->find($id);
        return response()->json($group);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $grp = Groupedt::find($id);
        $validated = $request->validate([
            'au_id' => 'exists:aus,id',
            'parcour_id' => 'exists:parcours,id',
            'semestre_id' => 'exists:semestres,id',

        ]);

        $grp->update($validated);
        return response()->json($grp);
    }

    public function getByIdAU($au_id,)
    {
        $grp = Groupedt::where('au_id', $au_id)
            ->with('au')
            ->with('parcour')
            ->with('semestre')
            ->with('edt')
            ->with('edt.enseignant')
            ->with('edt.heure')
            ->with('edt.salle')
            ->with('edt.jour')
            ->get();
        return response()->json($grp, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $edt = Groupedt::find($id);
        $edt->delete();
        return response()->json(['message' => 'Emploi du temps supprimé !']);
    }
}
