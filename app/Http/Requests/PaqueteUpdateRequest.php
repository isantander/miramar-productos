<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaqueteUpdateRequest extends FormRequest
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
            'servicios' => 'required|array|min:2',
            'servicios.*' => 'required|integer|exists:servicios,id|distinct',
        ];
    }

    public function messages(): array
    {
        return [
            'servicios.required' => 'Debe seleccionar al menos 2 servicios para el paquete',
            'servicios.min' => 'Un paquete debe tener mínimo 2 servicios',
            'servicios.*.exists' => 'Uno o más servicios seleccionados no existen',
        ];
    }
}
