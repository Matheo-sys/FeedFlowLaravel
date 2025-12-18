<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;
use Carbon\Carbon;
use App\Events\SurveyClosed;

class CheckForSurveyToClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'surveys:check-for-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérify and close surveys that have reached their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $this->info("Date/heure actuelle : " . $now->toDateTimeString());
        $this->info("Timezone : " . config('app.timezone'));

        $surveysToClose = Survey::where('status', 'active')
            ->where('end_date', '<=', $now)
            ->get();

        $this->info("Nombre de sondages actifs à vérifier : " . Survey::where('status', 'active')->count());

        foreach ($surveysToClose as $survey) {
            $this->info("Sondage trouvé - ID: {$survey->id}, Titre: {$survey->title}, End date: {$survey->end_date}");

            $survey->update(['status' => 'closed']);

            event(new SurveyClosed($survey));

            $this->info("Sondage #{$survey->id} fermé : {$survey->title}");
        }
        $this->info("Total : {$surveysToClose->count()} sondage(s) fermé(s)");
    }
}

