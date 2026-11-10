<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:150'],
            'descripcion' => ['required', 'string', 'min:10'],
            'prioridad' => ['required', Rule::in(Ticket::PRIORIDADES)],
            'categoria_id' => ['required', 'exists:categorias,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no puede superar :max caracteres.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'in' => 'El valor de :attribute no es válido.',
            'exists' => 'El valor seleccionado en :attribute no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'titulo' => 'título',
            'descripcion' => 'descripción',
            'prioridad' => 'prioridad',
            'categoria_id' => 'categoría',
        ];
    }
}
