<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInspectionRequest extends FormRequest
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
            'comments' => ['string', 'max:500'],
        ];
    }
}
