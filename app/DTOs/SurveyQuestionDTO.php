<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyQuestionDTO
{
    public function __construct(
        public string $title,
        public string $question_type,
        public ?array $options = null
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

        
        return new self(
            title: $data['title'],
            question_type: $data['question_type'],
            options: in_array($data['question_type'], ['single_choice', 'multiple_choice']) ? ($data['options'] ?? ['A']) : [],
        );s
    }
}
