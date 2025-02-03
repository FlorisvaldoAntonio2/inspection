<?php

namespace App\Http\Requests;

use App\Rules\UniquePartCode;
use Illuminate\Foundation\Http\FormRequest;

class StorePartRequest extends FormRequest
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
            'code' => ['required', 'string', 'min:3', 'max:100', new UniquePartCode($this->inspection_id)],
            'description' => ['required', 'string', 'min:3', 'max:1000'],
            'status' => ['required', 'string', 'in:good,bad'],
            'inspection_id' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'O campo código é obrigatório',
            'code.string' => 'O campo código deve ser uma string',
            'code.min' => 'O campo código deve ter no mínimo 3 caracteres',
            'code.max' => 'O campo código deve ter no máximo 100 caracteres',
            'description.required' => 'O campo descrição é obrigatório',
            'description.string' => 'O campo descrição deve ser uma string',
            'description.min' => 'O campo descrição deve ter no mínimo 3 caracteres',
            'description.max' => 'O campo descrição deve ter no máximo 1000 caracteres',
            'status.required' => 'O campo status é obrigatório',
            'status.string' => 'O campo status deve ser uma string',
            'status.in' => 'O campo status deve ser good ou bad'
        ];
    }
}
