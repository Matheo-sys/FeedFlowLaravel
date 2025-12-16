<?php

namespace App\Http\Controllers;

use App\Actions\Survey\StoreSurveyAction;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Models\Survey;
use Illuminate\View\View;
use App\DTOs\SurveyDTO;
use Illuminate\Http\Request;

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
        $survey= $action->execute($dto);
        return redirect()->route('surveys.index')->with('success', 'Survey created successfully');
    }
}
