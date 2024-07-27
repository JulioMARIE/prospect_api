<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'date_heure' => 'required|date_format:Y-m-d H:i:s',
            // 'date_heure' => 'required|date_format:yyyy-MM-dd HH:mm:ss',
            'personne_rencontree' => 'required|string|max:255',
            'contact_pers_rencont' => 'required|string|max:20|regex:/^[0-9\s\-\(\)]+$/',
            'fonction_pers_rencont' => 'required|string|max:255',
            'logiciels' => 'nullable|string',
            'observations' => 'nullable|string',
            'commercial_id' => 'required|exists:commercials,id',
            'societe_id' => 'required|exists:societes,id'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 0,
            'message' => 'Erreur de validation',
            'errors' => $validator->errors(),
        ], 422));
    }
}
