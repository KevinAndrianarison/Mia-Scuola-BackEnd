<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ec;
use Illuminate\Http\Request;

class EcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Ec::with('ue')
            ->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nom_ec' => 'nullable',
            'volume_et' => 'nullable',
            'volume_ed' => 'nullable',
            'volume_tp' => 'nullable',
            'ue_id' => 'required|exists:ues,id',
            'au_id' => 'required|exists:aus,id',
        ]);

        $existing = Ec::where('nom_ec', $request->nom_ec)
            ->where('ue_id', $request->ue_id)
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'EC déjà existante !']);
        }
        $ec = Ec::create($request->all());
        return response()->json($ec, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $ec = Ec::findOrFail($id);
        return response()->json($ec, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $ec = Ec::findOrFail($id);
        $request->validate([
            'nom_ec' => 'nullable',
            'volume_et' => 'nullable',
            'volume_ed' => 'nullable',
            'volume_tp' => 'nullable',
            'enseignant_id' => 'nullable|exists:enseignants,id',

        ]);
        if ($ec->enseignant_id) {
            $request->request->remove('enseignant_id');
            return response()->json(['message' => 'Un enseignant est déjà associé à ce EC !']);
        }
        $ec->update($request->all());
        return response()->json($ec, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $ec = Ec::findOrFail($id);
        $ec->delete();
        return response()->json(null, 204);
    }

    public function getByUeId($ue_id)
    {
        $ec = Ec::where('ue_id', $ue_id)->with('enseignant')->get();
        return response()->json($ec, 200);
    }

    public function getByEnsegnantId($enseignant_id)
    {
        $ec = Ec::where('enseignant_id', $enseignant_id)->get();
        return response()->json($ec, 200);
    }

    public function getByEnsegnantIdAndAU($enseignant_id, $au_id)
    {
        $ec = Ec::where('enseignant_id', $enseignant_id)
            ->where('au_id', $au_id)
            ->get();
        return response()->json($ec, 200);
    }

    public function clearEnseignantId($id)
    {
        $ec = Ec::findOrFail($id);
        $ec->enseignant_id = null;
        $ec->save();
        return response()->json(['message' => 'L\'enseignant a été dissocié avec succès !'], 200);
    }
    
}
