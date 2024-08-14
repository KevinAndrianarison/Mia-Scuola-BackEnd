<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Enseignant::with('user')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nomComplet_ens' => 'nullable',
            'date_recrutement_ens' => 'nullable',
            'telephone_ens' => 'nullable',
            'grade_ens' => 'nullable',
            'user_id' => 'required|exists:users,id'
        ]);
        $enseignant = Enseignant::create($request->all());

        return response()->json($enseignant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $enseignant = Enseignant::findOrFail($id);
        return response()->json($enseignant->load('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $enseignant = Enseignant::findOrFail($id);
        $request->validate([
            'nomComplet_ens' => 'nullable',
            'date_recrutement_ens' => 'nullable',
            'telephone_ens' => 'nullable',
            'grade_ens' => 'nullable',
            'user_id' => 'required|exists:users,id'
        ]);
        $enseignant->update($request->all());
        return response()->json($enseignant, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $enseignant = Enseignant::findOrFail($id);
        $enseignant->delete();
        return response()->json(null, 204);
    }
}
