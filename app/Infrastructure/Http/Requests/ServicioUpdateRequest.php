<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicioUpdateRequest extends FormRequest
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
            'codigo_servicio' => [
                'required',
                'string',
                'size:8', 
                'unique:servicios,codigo_servicio,' . $this->route('servicio'), // excluye el codigo actual para poder actualizar el registro
                'regex:/^[A-Z]{3}-\d{4}$/' // SRV-0001, TUR-0002
            ],        
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'destino' => 'required|string|max:100',
            'fecha' => 'required|date',
            'precio' => 'required|numeric|min:0|max:999999.99',
        ];
    }
}
