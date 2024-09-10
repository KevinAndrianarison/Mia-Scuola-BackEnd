<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Directeur;
use Illuminate\Http\Request;

class DirecteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Directeur::with('user')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nomComplet_dir' => 'nullable',
            'grade_dir' => 'nullable',
            'telephone_dir' => 'nullable',
            'user_id' => 'required|exists:users,id'
        ]);
        $directeur = Directeur::create($request->all());

        return response()->json($directeur, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $directeur = Directeur::findOrFail($id);
        return response()->json($directeur->load('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $directeur = Directeur::findOrFail($id);
        $request->validate([
            'nomComplet_dir' => 'nullable',
            'grade_dir' => 'nullable',
            'telephone_dir' => 'nullable',
        ]);
        $directeur->update($request->all());
        return response()->json($directeur, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $directeur = Directeur::findOrFail($id);
        $directeur->delete();
        return response()->json(null, 204);
    }

    public function getByUserId($user_id)
    {
        $directeur = Directeur::where('user_id', $user_id)->with('user')->get();
        return response()->json($directeur, 200);
    }

    public function getFirst()
    {
        return response()->json(Directeur::first(), 200);
    }
}
