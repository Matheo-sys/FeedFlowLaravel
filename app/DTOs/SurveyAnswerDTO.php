<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class SurveyAnswerDTO
{
    public function __construct(
        public readonly int $survey_id,
        public readonly int|null $user_id,
        public readonly array $answers
    ) {
    }

    public static function fromRequest(Request $request, int $survey_id)
    {
        return new self(
            survey_id: $survey_id,
            user_id: $request->user()?->id,
            answers: $request->input('answers', [])
        );
    }
}
