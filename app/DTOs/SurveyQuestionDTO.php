<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    public function __construct(
        public string $title,
        public string $question_type,
        public ?array $options = []
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->input('title'),
            question_type: $request->input('question_type'),
            options: in_array($request->input('question_type'), ['single_choice', 'multiple_choice']) ? $request->input('options', []) : [],
        );
    }

    public static function fromArray(array $data): self
    {
        // 1. On sécurise le type de question (au cas où la clé manque)
        $type = $data['question_type'] ?? 'text';

        // 2. On détermine si ce type a besoin d'options
        $isChoiceType = in_array($type, ['single_choice', 'multiple_choice']);
        
        return new self(
            title: $data['title'],
            question_type: $data['question_type'],
            options: in_array($data['question_type'], ['single_choice', 'multiple_choice']) ? ($data['options'] ?? ['A']) : [],
        );
    }
}
