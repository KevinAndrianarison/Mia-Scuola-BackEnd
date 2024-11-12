<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Com;
use Illuminate\Http\Request;

class ComController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(
            Com::with('user')
                ->get(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'contenu' => 'nullable',
            'annonce_id' => 'required|exists:annonces,id',
            'user_id' => 'required|exists:users,id',

        ]);
        $coms = Com::create($request->all());
        return response()->json($coms, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $coms = Com::findOrFail($id);
        $request->validate([
            'contenu' => 'nullable',
        ]);
        $coms->update($request->all());
        return response()->json($coms, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $coms = Com::findOrFail($id);
        $coms->delete();
        return response()->json(null, 204);
    }

    public function getAnnonceByIdAnnonce($annonce_id)
    {
        $coms = Com::where('annonce_id', $annonce_id)
            ->with('user')
            ->get();

        return response()->json($coms, 200);
    }
}
