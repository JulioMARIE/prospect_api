<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProspectionRequest extends FormRequest
{
    public function authorize()
    {
        $user = Auth::guard('sanctum')->user();
        return $user && isset($user->responsable);
    }

    public function rules()
    {
        return [
            'date_heure' => 'required|date',
            'personne_rencontree' => 'required|string|max:255',
            'contact_pers_rencont' => 'required|string|max:255',
            'fonction_pers_rencont' => 'required|string|max:255',
            'logiciels' => 'nullable|string',
            'observations' => 'nullable|string',
            'societe_id' => 'required|exists:societes,id',
            'commercial_id' => 'required|exists:commercials,id',
        ];
    }
}
