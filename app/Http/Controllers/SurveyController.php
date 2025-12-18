<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Models\Survey;
use Carbon\Carbon;
use Illuminate\View\View;
use App\DTOs\SurveyDTO;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SurveyController extends Controller
{
    public function index() : View {
        $surveys = Survey::all();
        return view('surveys.index', compact('surveys'));
    }

    public function create(): View {
        return view('surveys.create');  
    }

    public function store(StoreSurveyRequest $request, StoreSurveyAction $action)
    {
        $dto = SurveyDTO::formRequest($request);
        $action->execute($dto);
        return redirect()->route('surveys.index')->with('success', 'Survey created successfully');
    }

    public function show(Survey $survey): View {
        return view('surveys.show', compact('survey'));
    }

    public function edit(Survey $survey): View {
        return view('surveys.edit', compact('survey'));
    }

    public function update(Request $request, Survey $survey)
    {
        $survey->update($request->all());
        return redirect()->route('surveys.index')->with('success', 'Survey updated successfully');
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();
        return redirect()->route('surveys.index')->with('success', 'Survey deleted successfully');
    }
    public function showByToken(Request $request, string $token): View|RedirectResponse
    {

        $survey = Survey::where('token', $token)->firstOrFail();

        // Valide la période d'activité.
        if ($survey->start_date && $survey->end_date) {
            $now = Carbon::now();
            $startDate = Carbon::parse($survey->start_date)->startOfDay();
            $endDate = Carbon::parse($survey->end_date)->endOfDay();

            if (!$now->between($startDate, $endDate)) {
                abort(403, 'Ce sondage n\'est pas ou plus actif .');
            }
        }

        // Pour les sondages non anonymes, une connexion est requise.
        if (! $survey->is_anonymous && ! auth()->check()) {
            return redirect()->route('login')
                ->with('status', 'Veuillez vous connecter pour répondre à ce sondage.');
        }

        // Si le sondage est trouvé, la vue correspondante est affichée avec les données du sondage.
        return view('surveys.public_show', [
            'survey' => $survey,
            //"questions" est temporairement désactivée pour les tests.
            'questions' => collect(),
        ]);
    }
}
