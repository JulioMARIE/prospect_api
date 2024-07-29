<?php

namespace App\Http\Controllers;

use App\Models\Quota;
use App\Http\Requests\StoreQuotaRequest;
use App\Http\Requests\UpdateQuotaRequest;
use App\Models\Responsable;
use App\Models\Utilisateur;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;


class QuotaController extends Controller
{
    public function index()
    {
        return Quota::all();
    }

    public function store(StoreQuotaRequest $request, Utilisateur $u)
    {
        // return $u->responsable;

            //ajoutes des policies ou traits pour gérer les droits d'accès;
            $r = $u->responsable;

            if ($r != null)  {
                $commercial_id = $request->input('commercial_id');
                $date_debut = Carbon::parse($request->input('date_debut'))->format('Y-m-d');
                // return $date_debut;
                $date_fin = Carbon::parse($request->input('date_fin'))->format('Y-m-d');

                // Vérifier s'il existe déjà un quota qui chevauche la période
                $chevauchement = Quota::where('commercial_id', $commercial_id)
                    ->where(function ($query) use ($date_debut, $date_fin) {
                        $query->whereBetween('date_debut', [$date_debut, $date_fin])
                            ->orWhereBetween('date_fin', [$date_debut, $date_fin])
                            ->orWhere(function ($query) use ($date_debut, $date_fin) {
                                $query->where('date_debut', '<=', $date_debut)
                                      ->where('date_fin', '>=', $date_fin);
                            });
                    })
                    ->exists();

                // return $chevauchement;

                if ($chevauchement) {
                    return response()->json([
                                            'erroCode' => 0,
                        'message' => 'Il existe déjà un quota de cette période ou une période qui chevauche pour ce commercial',
                    ], 422);
                }
                
                $q = Quota::create($request->all());
                return response()->json(['successCode' => 1, 'quota' => $q], 201);
            }
            return response()->json(['errorCode' => 0, 'message' => 'Utilisateur non responsable'], 404);        
}
    public function show(Quota $quota)
    {
        return $quota;
    }

    public function update(UpdateQuotaRequest $request, Quota $quota)
    {
        $quota->update($request->all());
        return $quota;
    }

    public function destroy(Quota $quota)
    {
        $quota->delete();
        return response()->json(['message' => 'Quota supprimé']);
    }
}

