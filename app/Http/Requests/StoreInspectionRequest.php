<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
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
            'description' => ['required', 'string', 'min:3', 'max:1000'],
            'inspection_start' => ['required', 'date'],
            'inspection_end' => ['required', 'date'],
            'attempts_per_operator' => ['required', 'integer'],
            'quantity_pieces' => ['integer'],
            'operators' => ['required', 'array'],
            'product' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        //em pt
        return [
            'description.required' => 'A descrição é obrigatória',
            'description.min' => 'A descrição deve ter no mínimo 3 caracteres',
            'description.max' => 'A descrição deve ter no máximo 1000 caracteres',
            'inspection_start.required' => 'A data de início da inspeção é obrigatória',
            'inspection_start.date' => 'A data de início da inspeção deve ser uma data válida',
            'inspection_end.required' => 'A data de fim da inspeção é obrigatória',
            'inspection_end.date' => 'A data de fim da inspeção deve ser uma data válida',
            'attempts_per_operator.required' => 'O número de tentativas por operador é obrigatório',
            'attempts_per_operator.integer' => 'O número de tentativas por operador deve ser um número inteiro',
            'quantity_pieces.integer' => 'A quantidade de peças deve ser um número inteiro',
            'operators.required' => 'Pelos menos um operador deve ser selecionado',
            'product.required' => 'O produto é obrigatório',
            'product.max' => 'O produto deve ter no máximo 255 caracteres',
        ];
    }
}
