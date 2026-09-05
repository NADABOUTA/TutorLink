<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DemandeRequest extends FormRequest
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
            'matiere' => ['required', 'string', 'max:100'],
            'niveau' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'budget' => ['required', 'numeric', 'min:1', 'max:10000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'matiere' => 'matière',
            'niveau' => 'niveau scolaire',
            'description' => 'description du besoin',
            'budget' => 'budget proposé',
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'description.min' => 'La description doit comporter au moins :min caractères pour permettre aux tuteurs de bien comprendre votre besoin.',
            'budget.min' => 'Le budget proposé doit être supérieur ou égal à :min DH.',
        ];
    }
}
