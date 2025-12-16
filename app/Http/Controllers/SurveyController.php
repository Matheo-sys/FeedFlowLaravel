<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\View\View;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index() : View {
        $surveys = Survey::all();
        return view('surveys.index', compact('surveys'));
    }
}
