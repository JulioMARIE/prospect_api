<?php

namespace App\Http\Controllers;

use App\Models\Quota;
use App\Http\Requests\StoreQuotaRequest;
use App\Http\Requests\UpdateQuotaRequest;


class QuotaController extends Controller
{
    public function index()
    {
        return Quota::all();
    }

    public function store(StoreQuotaRequest $request)
    {
        
        $validated = $request->validate([
            'commercial_id' => 'required|exists:commercials,id',
            'objectif' => 'required|numeric',
            'periode' => 'required|date',
        ]);
        return Quota::create($validated);
    }

    public function show(Quota $quota)
    {
        return $quota;
    }

    public function update(UpdateQuotaRequest $request, Quota $quota)
    {
        
        $validated = $request->validate([
            'objectif' => 'numeric',
            'periode' => 'date',
            // Ajoutez d'autres champs selon votre modèle
        ]);
        $quota->update($validated);
        return $quota;
    }

    public function destroy(Quota $quota)
    {
        $quota->delete();
        return response()->json(['message' => 'Quota supprimé']);
    }
}

