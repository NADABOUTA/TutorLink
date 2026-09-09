<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OffreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isTuteur() || $this->user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tarif_propose' => ['required', 'numeric', 'min:1', 'max:10000'],
            'message' => ['required', 'string', 'min:15', 'max:1500'],
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
            'tarif_propose' => 'tarif proposé',
            'message' => 'message de candidature',
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
            'message.min' => 'Votre message de présentation doit comporter au moins :min caractères pour rassurer l\'apprenant sur vos compétences.',
            'tarif_propose.min' => 'Le tarif proposé doit être d\'au moins :min DH.',
        ];
    }
}
