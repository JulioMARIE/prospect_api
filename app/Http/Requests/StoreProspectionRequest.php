<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Auth;

class StoreProspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
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
