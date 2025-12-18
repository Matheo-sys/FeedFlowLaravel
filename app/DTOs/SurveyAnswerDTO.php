<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use App\Http\Requests\Survey\StoreSurveyAnswerRequest;


final class SurveyAnswerDTO
{
    public function __construct(
        public readonly array $answers
    ) {}

    public static function fromRequest(StoreSurveyAnswerRequest $request): self
    {
        // On récupère uniquement le tableau validé 'answers'
        return new self(
            answers: $request->validated('answers') ?? []
        );
    }
}