<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use Illuminate\Support\Facades\DB;

final class StoreSurveyAnswerAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param SurveyDTO $dto
     * @return array
     */
    public function handle(SurveyDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            return SurveyAnswer::create([
                'survey_id' => $dto->survey_id,
                'user_id' => $dto->user_id,
                'answer' => $dto->answer,
                'question_id' => $dto->question_id
                'created_at' => $dto->created_at,
                'updated_at' => $dto->updated_at,
            ]);
                
        });
    }
}
