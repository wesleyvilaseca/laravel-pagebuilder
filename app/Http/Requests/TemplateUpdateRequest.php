<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');

        return [
            'name' => 'required|string|unique:templates,name,' . $id,
            'description' => 'required|string',
            'theme_id' => 'required|integer|exists:themes,id',
            'status' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.unique' => 'O nome já está em uso.',
            'description.required' => 'O campo descrição é obrigatório.',
            'description.string' => 'O campo descrição deve ser uma string.',
            'theme_id.required' => 'O campo theme_id é obrigatório.',
            'theme_id.integer' => 'O campo theme_id deve ser um número inteiro.',
            'theme_id.exists' => 'O theme_id selecionado é inválido.',
        ];
    }
}
