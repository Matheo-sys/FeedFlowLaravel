<?php
namespace App\Actions\Survey;

use App\DTOs\SurveyDTO;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Models\Survey;
use Illuminate\Validation\ValidationException;

final class StoreSurveyAction
{
    public function __construct() {}

    /**
     * Store a Survey
     * @param StoreSurveyRequest $request
     * @return array
     */
    public function execute(SurveyDTO $dto): Survey
    {
            
            $survey = Survey::create([
                'user_id' => $dto->user_id,
                'organization_id' => $dto->organization_id,
                'title' => $dto->title,
                'description' => $dto->description,
                'start_date' => $dto->start_date,
                'end_date' => $dto->end_date,
                'is_anonymous' => $dto->is_anonymous,
            ]);
            
            return $survey;
    }
}
