<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use App\Http\Requests\StoreSocieteRequest;
use App\Http\Requests\UpdateSocieteRequest;

class SocieteController extends Controller
{
    public function index()
    {
        return response()->json(Societe::all());
    }

    public function store(StoreSocieteRequest $request)
    {
        // return $request;
        
        $societe = Societe::create($request->all());
        
        return response()->json($societe, 201);
    }

    public function show(Societe $societe)
    {
        return response()->json($societe);
    }

    public function update(UpdateSocieteRequest $request, Societe $societe)
    {
        $validated = $request->validated();
        $societe->update($validated);
        return response()->json($societe);
    }

    public function destroy(Societe $societe)
    {
        $societe->delete();
        return response()->json(['message' => 'Société supprimée'], 200);
    }
}