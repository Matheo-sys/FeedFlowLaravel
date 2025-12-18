<?php
namespace App\Actions\Survey;

use Illuminate\Support\Facades\DB;
use App\Models\SurveyQuestion;
use App\DTOs\SurveyQuestionDTO;

final class StoreSurveyQuestionAction
{
    public function __construct()
    {
    }

    /**
     * Store a Survey Question
     * @param SurveyQuestionDTO $dto
     * @param int $surveyId
     * @return SurveyQuestion
     */
    public function handle(SurveyQuestionDTO $dto, int $surveyId): SurveyQuestion
    {
        return DB::transaction(function () use ($dto, $surveyId) {
            return SurveyQuestion::create([
                'survey_id' => $surveyId,
                'title' => $dto->title,
                'question_type' => $dto->question_type,
                'options' => $dto->options,
            ]);
        });
    }
}
