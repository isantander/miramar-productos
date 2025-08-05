<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaqueteStoreRequest extends FormRequest
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
            'servicios' => 'required|array|min:2', // Mínimo 2 servicios para que sea un paquete
            'servicios.*' => 'required|integer|exists:servicios,id', // validar que los id's existan
            'codigo' => 'nullable|string|unique:paquetes,codigo',
            'nombre' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'servicios.required' => 'Debe seleccionar al menos dos servicios para crear un paquete',
            'servicios.min' => 'Debe seleccionar al menos dos servicios para crear un paquete',
            'servicios.*.exists' => 'Uno o más servicios seleccionados no existen',
            'servicops.*.required' => 'Todos los servicios seleccionados son oblitatorios',            
        ];
    }
}
