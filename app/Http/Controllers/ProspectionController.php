<?php

namespace App\Http\Controllers;

use App\Models\Prospection;
use App\Http\Requests\StoreProspectionRequest;
use App\Http\Requests\UpdateProspectionRequest;
use Illuminate\Http\Request;

class ProspectionController extends Controller
{
    public function index()
    {
        return Prospection::all();
    }

    public function store(StoreProspectionRequest $request)
    {
        $validated = $request->validate([
            'client_potentiel' => 'required|string',
            'date_contact' => 'required|date',
            // Ajoutez d'autres champs selon votre modèle
        ]);
        return Prospection::create($validated);
    }

    public function show(Prospection $prospection)
    {
        return $prospection;
    }

    public function update(UpdateProspectionRequest $request, Prospection $prospection)
    {
        $validated = $request->validate([
            'client_potentiel' => 'string',
            'date_contact' => 'date',
            // Ajoutez d'autres champs selon votre modèle
        ]);
        $prospection->update($validated);
        return $prospection;
    }

    public function destroy(Prospection $prospection)
    {
        $prospection->delete();
        return response()->json(['message' => 'Prospection supprimée']);
    }

    public function mesProspections(Request $request)
    {
        return $request->user()->prospections;
    }

    public function ajouterSuivi(Request $request, Prospection $prospection)
    {
        $validated = $request->validate([
            'commentaire' => 'required|string',
            // Ajoutez d'autres champs pour le suivi
        ]);
        $suivi = $prospection->suivis()->create($validated);
        return response()->json(['message' => 'Suivi ajouté', 'suivi' => $suivi]);
    }

}
