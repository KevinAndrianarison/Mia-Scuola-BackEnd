<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cursu;
use Illuminate\Http\Request;

class CursuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Cursu::with('etudiant')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $cursus = Cursu::create($request->all());
        return response()->json($cursus, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $cursus = Cursu::findOrFail($id);
        return response()->json($cursus->load('etudiant'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $cursus = Cursu::findOrFail($id);
        $cursus->delete();
        return response()->json(null, 204);
    }
}
