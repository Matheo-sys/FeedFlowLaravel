<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OrganizationUser;
use App\Models\Survey;


class StoreSurveyQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->can('create', Survey::class)) {
            return true;
        }
        return false;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'question_type' => 'required|in:single_choice,multiple_choice,text,scale_1_10',
            'options' => 'nullable|array|required_if:question_type,single_choice,multiple_choice',
            'options.*' => 'string|max:255',
        ];
    }
}
