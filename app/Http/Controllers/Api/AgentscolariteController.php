<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Agentscolarite;
use Illuminate\Http\Request;

class AgentscolariteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Agentscolarite::with('user')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nomComplet_scol' => 'nullable',
            'date_recrutement_scol' => 'nullable',
            'telephone_scol' => 'nullable',
            'categorie_scol' => 'nullable',
            'user_id' => 'required|exists:users,id'
        ]);
        $agentscolarite = Agentscolarite::create($request->all());

        return response()->json($agentscolarite, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $agentscolarite = Agentscolarite::findOrFail($id);
        return response()->json($agentscolarite->load('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $agentscolarite = Agentscolarite::findOrFail($id);
        $request->validate([
            'nomComplet_scol' => 'nullable',
            'date_recrutement_scol' => 'nullable',
            'telephone_scol' => 'nullable',
            'categorie_scol' => 'nullable',
        ]);
        $agentscolarite->update($request->all());
        return response()->json($agentscolarite, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $agentscolarite = Agentscolarite::findOrFail($id);
        $agentscolarite->delete();
        return response()->json(null, 204);
    }

    public function getByUserId($user_id)
    {
        $agentscolarite = Agentscolarite::where('user_id', $user_id)->with('user')->get();
        return response()->json($agentscolarite, 200);
    }
}
