<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Models\Survey;
use Illuminate\View\View;
use App\DTOs\SurveyDTO;
use Illuminate\Http\Request;
use App\Actions\Survey\StoreSurveyQuestionAction;
use App\Http\Requests\Survey\StoreSurveyQuestionRequest;
use App\DTOs\SurveyQuestionDTO;

class SurveyController extends Controller
{
public function index(): View
    {
        $this->authorize('viewAny', Survey::class);
        $user = auth()->user();
        
        $surveys = Survey::where('organization_id', $user->organization_id)
            ->with('questions') // On charge les relations directement dans la requête
            ->orderBy('created_at', 'desc')
            ->get(); // IMPORTANT : On exécute la requête avec get()

        return view('surveys.index', compact('surveys'));
    }

    public function create(): View
    {
        $this->authorize('create', Survey::class);
        return view('surveys.create');
    }

    public function store(Request $request, StoreSurveyAction $action, StoreSurveyQuestionAction $questionAction)
        {
            $this->authorize('create', Survey::class);

            // Handle JSON request from Alpine.js
            if ($request->wantsJson() || $request->isJson()) {
                $surveyData = $request->only(['title', 'description', 'start_date', 'end_date', 'is_anonymous']);
                $questions = $request->input('questions', []);


                // Create survey
                $dto = SurveyDTO::formArray($surveyData);
                $survey = $action->execute($dto);

                // Create questions for the survey
                $createdQuestions = [];
                foreach ($questions as $index => $questionData) {
                    \Log::info("Processing question {$index}", ['data' => $questionData]);

                    if (!empty($questionData['title'])) {
                        $questionDTO = SurveyQuestionDTO::fromArray($questionData);
                        $question = $questionAction->handle($questionDTO, $survey->id);
                        $createdQuestions[] = $question;

                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Survey created successfully',
                    'survey' => $survey,
                    'questions_created' => count($createdQuestions)
                ], 201);
            }

            // Handle traditional form request
            $dto = SurveyDTO::formRequest($request);
            $action->execute($dto);
            return redirect()->route('surveys.index')->with('success', 'Survey created successfully');
        }


    public function show(Survey $survey): View
    {
        $this->authorize('view', $survey);
        $survey->load('questions');
        return view('surveys.show', compact('survey'));
    }

    public function edit(Survey $survey): View
    {
        $this->authorize('update', $survey);
        return view('surveys.edit', compact('survey'));
    }

    public function update(Request $request, Survey $survey)
    {
        $this->authorize('update', $survey);
        $survey->update($request->all());
        return redirect()->route('surveys.index')->with('success', 'Survey updated successfully');
    }

    public function destroy(Survey $survey)
    {
        $this->authorize('delete', $survey);
        $survey->delete();
        return redirect()->route('surveys.index')->with('success', 'Survey deleted successfully');
    }

    public function storeQuestion(StoreSurveyQuestionRequest $request, int $surveyId, StoreSurveyQuestionAction $action)
    {
        $survey = Survey::findOrFail($surveyId);
        $this->authorize('create', Survey::class);
        $dto = SurveyQuestionDTO::fromRequest($request);
        $question = $action->handle($dto, $surveyId);

        return response()->json(['message' => 'Question created', 'question' => $question], 201);
    }
}
