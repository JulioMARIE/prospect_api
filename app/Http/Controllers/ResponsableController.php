<?php

namespace App\Http\Controllers;

use App\Models\Responsable;
use Illuminate\Http\Request;

class ResponsableController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', Responsable::class);
        return Responsable::with('utilisateur')->get();
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Responsable::class);
        $validated = $request->validate([
            'nom' => 'required|string',
            'email' => 'required|email|unique:responsables',
            // Ajoutez d'autres champs selon votre modèle
        ]);
        return Responsable::create($validated);
    }

    public function show(Responsable $responsable)
    {
        // $this->authorize('view', $responsable);
        return $responsable;
    }

    public function update(Request $request, Responsable $responsable)
    {
        // $this->authorize('update', $responsable);
        $validated = $request->validate([
            'nom' => 'string',
            'email' => 'email|unique:responsables,email,'.$responsable->id,
            // Ajoutez d'autres champs selon votre modèle
        ]);
        $responsable->update($validated);
        return $responsable;
    }

    public function destroy(Responsable $responsable)
    {
        // $this->authorize('delete', $responsable);
        $responsable->delete();
        return response()->json(['message' => 'Responsable supprimé']);
    }
}