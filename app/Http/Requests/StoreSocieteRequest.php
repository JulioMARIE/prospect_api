<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocieteRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'denomination' => 'required|string|max:255',
            'raison_sociale' => 'required|string|max:255',
            'IFU' => 'required|string|unique:societes,IFU|max:255',
            'description_siege' => 'required|string|max:255',
            'commune_id' => 'required|exists:communes,id',
        ];
    }
}
