<?php

namespace App\Http\Controllers;

use App\Models\Prospection;
use App\Http\Requests\StoreProspectionRequest;
// use App\Http\Requests\UpdateProspectionRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSuiviRequest;
use Illuminate\Support\Facades\Auth;

class ProspectionController extends Controller
{
    public function index()
    {
        $user = Auth::guard('sanctum')->user();
        if($user && isset($user->responsable)) {
            $prospections = Prospection::with([
                'commercial.utilisateur',
                'societe',
                'suivis'
            ])->get();
            return response()->json($prospections);
        }
    }

    public function store(StoreProspectionRequest $request)
    {
        // return $request;
        $prospection = Prospection::create($request->all());
        
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

    public function ajouterSuivi(StoreSuiviRequest $request, Prospection $prospection)
    {
        // $validated = $request->validate([
        //     'date_heure' => 'required|date_format:Y-m-d H:i:s',
        //     'observation' => 'required|string|max:255',
        //     'prospection_id' => 'required|exists:prospections,id',
        // ]);

        // // return "ok";

        // return $request['prospection_id'];

    
        if ($request['prospection_id'] != $prospection->id) {
            return response()->json(['message' => 'ID de prospection non valide'], 400);
        }

        $suivi = $prospection->suivis()->create($request->all());
        return response()->json(['successCode' => 1, 'suivi' => $suivi], 201);
    }

}