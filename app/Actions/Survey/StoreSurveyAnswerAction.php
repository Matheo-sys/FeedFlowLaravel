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
public function handle(SurveyAnswerDTO $dto, Survey $survey): void
    {

        DB::transaction(function () use ($dto, $survey) {
            foreach ($dto->answers as $questionId => $value) {
                
                // Si c'est un tableau (checkbox), on le transforme en JSON
                $finalValue = is_array($value) ? json_encode($value) : $value;

                SurveyAnswer::create([
                    'survey_id' => $survey->id,
                    'survey_question_id' => $questionId,
                    'user_id' => auth()->id(),
                    'answer' => $finalValue, // La virgule importante est ici
                ]);
            }
        });
    }
}
