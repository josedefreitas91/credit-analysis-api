<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:75',
            'cpf' => 'required|string|max:14|regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/',
            'negative' => 'required|boolean',
            'salary' => 'required|numeric',
            'card_limit' => 'required|numeric',
            'rent_value' => 'required|numeric',
            'road' => 'required|string|max:120',
            'number' => 'required|integer',
            'city' => 'required|string|max:75',
            'federative_unit' => 'required|string|max:2',
            'cep' => 'required|string|max:9|regex:/^\d{5}\-\d{3}$/',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors()
            ])->setStatusCode(400)
        );
    }
}
