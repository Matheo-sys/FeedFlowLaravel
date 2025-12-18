<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Mail\SurveyDailyReport;
use Carbon\Carbon;
class SendSurveyDailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-survey-daily-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yesterday = Carbon::today();
        //$yesterday = Carbon::yesterday();
        $start = $yesterday->copy()->startOfDay();
        $end = $yesterday->copy()->endOfDay();

        $this->info("Analyse des réponses pour la journée du : " . $yesterday->toDateString());

        $stats = SurveyAnswer::select('survey_id')
            ->selectRaw('count(distinct user_id) as response_count')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('survey_id')
            ->having('response_count', '>=', 1)//10
            ->get();

        $this->info("Nombre de sondages éligibles (>10 réponses) : " . $stats->count());

        foreach ($stats as $stat) {
            $survey = Survey::with('user')->find($stat->survey_id);

            if ($survey && $survey->user) {
                Mail::to($survey->user->email)
                    ->send(new SurveyDailyReport($survey, $stat->response_count));

                $this->info("Rapport envoyé pour le sondage '{$survey->title}' ({$stat->response_count} réponses) à {$survey->user->email}");
            }
        }
    }
}
