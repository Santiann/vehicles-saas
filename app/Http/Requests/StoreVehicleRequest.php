<?php

namespace App\Http\Requests;

use App\Enums\Cambio;
use App\Enums\Combustivel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'placa' => [
                'required',
                'string',
                'max:7',
                'regex:/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/i',
                'unique:vehicles,placa',
            ],
            'chassi' => [
                'required',
                'string',
                'size:17',
                'regex:/^[A-HJ-NPR-Z0-9]{17}$/i', // Alphanumeric without I, O, Q
                'unique:vehicles,chassi',
            ],
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'versao' => 'required|string|max:100',
            'valor_venda' => 'required|numeric|min:0.01|max:99999999999999.99',
            'cor' => 'required|string|max:50',
            'km' => 'required|integer|min:0',
            'cambio' => ['required', new Enum(Cambio::class)],
            'combustivel' => ['required', new Enum(Combustivel::class)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'placa.required' => 'A placa é obrigatória.',
            'placa.regex' => 'A placa deve estar no formato Mercosul (ex: ABC1D23).',
            'placa.unique' => 'Esta placa já está cadastrada.',
            'chassi.required' => 'O chassi é obrigatório.',
            'chassi.size' => 'O chassi deve ter exatamente 17 caracteres.',
            'chassi.regex' => 'O chassi deve conter apenas letras (exceto I, O, Q) e números.',
            'chassi.unique' => 'Este chassi já está cadastrado.',
            'marca.required' => 'A marca é obrigatória.',
            'modelo.required' => 'O modelo é obrigatório.',
            'versao.required' => 'A versão é obrigatória.',
            'valor_venda.required' => 'O valor de venda é obrigatório.',
            'valor_venda.min' => 'O valor de venda deve ser pelo menos R$ 0,01.',
            'cor.required' => 'A cor é obrigatória.',
            'km.required' => 'A quilometragem é obrigatória.',
            'km.min' => 'A quilometragem não pode ser negativa.',
            'cambio.required' => 'O tipo de câmbio é obrigatório.',
            'combustivel.required' => 'O tipo de combustível é obrigatório.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'placa' => strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $this->placa ?? '')),
            'chassi' => strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $this->chassi ?? '')),
        ]);
    }
}
