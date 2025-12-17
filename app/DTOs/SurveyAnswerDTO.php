<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class SurveyAnswerDTO
{
    private function __construct(
    ) {
        public readonly int id;
        public readonly int survey_id;
        public readonly int survey_question_id;
        public readonly int user_id;
        public readonly string answer;
        public readonly string created_at;
        public readonly string updated_at;

    }

    public static function fromRequest(StoreSurveyAnswerRequest $request): self
    {
        return new self(
            survey_id: $request->survey_id,
            survey_question_id: $request->survey_question_id,
            user_id: $request->user()->id,
            answer: $request->answer,
            created_at: $request->created_at,
            updated_at: $request->updated_at,
        );
    }
}
