<?php

namespace App\Http\Controllers;

use App\Models\Quota;
use App\Http\Requests\StoreQuotaRequest;
use App\Http\Requests\UpdateQuotaRequest;
use App\Models\Responsable;
use App\Models\Utilisateur;



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

            if ($r !=null) {
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

