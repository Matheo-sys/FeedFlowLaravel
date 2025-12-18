<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyAnswerRequest extends FormRequest
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
            // 1. On autorise le champ 'answers' et on exige que ce soit un tableau
            'answers' => ['required', 'array'],

            // 2. On autorise le contenu du tableau (les réponses individuelles)
            // Le '*' signifie "pour chaque ID de question dans le tableau"
            'answers.*' => ['nullable'], 
        ];
    }
}
