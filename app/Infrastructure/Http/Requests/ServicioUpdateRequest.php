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
            'codigo' => ['required',
                        'string',
                        'max:20',
                        'unique:servicios,codigo,' . $this->route('servicio')                
            ],
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'destino' => 'required|string|max:100',
            'fecha' => 'required|date',
            'costo' => 'required|numeric|min:0|max:999999.99',
        ];
    }

    protected function prepareForValidation()
    {
        /* 
         Si bien la estandar  Rest indica que el verbo PUT debe tener esta estrucutra /api/servicios/{id} y body sin ID,
         las especficaciones del TP son claras y las debo respetar, por eso valido que los ids coincidan.
        */
        if ($this->input('id') && $this->input('id') != $this->route('servicio')) {
            abort(400, 'ID en URL y body no coinciden');
        }
    }

}
