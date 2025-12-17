<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyAnswerDTO;
use App\Events\SurveyAnswerSubmitted;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Support\Facades\DB;

final class StoreSurveyAnswerAction
{
    /**
     * Store a Survey Answer
     * @param SurveyAnswerDTO $dto
     * @return array
     */
    public function handle(SurveyAnswerDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            $survey = Survey::findOrFail($dto->survey_id);

            // Store each answer
            foreach ($dto->answers as $answerData) {
                SurveyAnswer::create([
                    'survey_id' => $survey->id,
                    'survey_question_id' => $answerData['question_id'],
                    'user_id' => $dto->user_id,
                    'answer' => $answerData['answer'],
                ]);
            }

            // Dispatch event for email notification
            SurveyAnswerSubmitted::dispatch($survey);

            return ['status' => 'success', 'message' => 'Answers submitted successfully'];
        });
    }
}
