<?php

namespace App\Http\Controllers;

use App\Models\Prospection;
use App\Http\Requests\StoreProspectionRequest;
// use App\Http\Requests\UpdateProspectionRequest;
use Illuminate\Http\Request;

class ProspectionController extends Controller
{
    public function index()
    {
        return response()->json(Prospection::all());
    }

    public function store(StoreProspectionRequest $request)
    {
        $validated = $request->validated();
        $prospection = Prospection::create($validated);
        return response()->json(['successCode' => 1, 'prospection' => $prospection], 201);
    }

    public function show(Prospection $prospection)
    {
        return response()->json($prospection);
    }

    // public function update(UpdateProspectionRequest $request, Prospection $prospection)
    // {
    //     $validated = $request->validated();
    //     $prospection->update($validated);
    //     return response()->json($prospection);
    // }

    public function destroy(Prospection $prospection)
    {
        $prospection->delete();
        return response()->json(['message' => 'Prospection supprimée'], 200);
    }

    public function mesProspections(Request $request)
    {
        return response()->json($request->user()->prospections);
    }

    public function ajouterSuivi(Request $request, Prospection $prospection)
    {
        $validated = $request->validate([
            'date_heure' => 'required|date',
            'observations' => 'required|string',
        ]);

        $suivi = $prospection->suivis()->create($validated);
        return response()->json(['message' => 'Suivi ajouté', 'suivi' => $suivi], 201);
    }
}