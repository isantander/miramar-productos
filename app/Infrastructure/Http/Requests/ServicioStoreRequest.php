<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicioStoreRequest extends FormRequest
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
            'codigo' => 'required|string|max:20|unique:servicios,codigo', 
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'destino' => 'required|string|max:100',
            'fecha' => 'required|date',
            'costo' => 'required|numeric|min:0|max:999999.99',
        ];
    }
}
