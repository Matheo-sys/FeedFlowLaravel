<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\SurveyClosed;
use App\Mail\SurveyFinalReport;
use Illuminate\Support\Facades\Mail;

class SendFinalReportOnClose
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SurveyClosed $event): void
    {
        $survey = $event->survey;

        $reportData = [
            'survey' => $survey,
            'total_responses' => $survey->responses()->count(),
            'statistics' => $this->calculateStatistics($survey),
        ];

        Mail::to($survey->user->email)
            ->send(new SurveyFinalReport($reportData));
    }

    private function calculateStatistics($survey)
    {
        return $survey->questions()->with('responses')->get()->map(function ($question) {
            return [
                'question' => $question->text,
                'response_count' => $question->responses->count(),
            ];
        });
    }
}
