<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreQuotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_debut' => 'required|date_format:Y-m-d',
            'date_fin' => 'required|date_format:Y-m-d|after_or_equal:date_debut',
            'nombre_fixe' => 'required|numeric',
            'commercial_id' => 'required|exists:commercials,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errorCode' => 0,
            'message' => 'Erreur de validation',
            'errors' => $validator->errors(),
        ], 422));
    }

    public function messages(): array
    {
        return [
            'date_debut.required' => 'La date de début est requise.',
            'date_debut.date_format' => 'La date de début doit être au format Y-m-d.',
            'date_fin.required' => 'La date de fin est requise.',
            'date_fin.date_format' => 'La date de fin doit être au format Y-m-d.',
            'date_fin.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
            'nombre_fixe.required' => 'Le nombre fixe est requis.',
            'nombre_fixe.numeric' => 'Le nombre fixe doit être un nombre.',
            'commercial_id.required' => 'L\'ID du commercial est requis.',
            'commercial_id.exists' => 'L\'ID du commercial sélectionné n\'existe pas.',
        ];
    }
}