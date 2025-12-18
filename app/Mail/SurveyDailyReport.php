<?php

namespace App\Mail;

use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SurveyDailyReport extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Survey $survey,
        public int $count
    ) {
    }

    public function build()
    {
        return $this->subject("Rapport quotidien: {$this->survey->title}")
            ->view('mail.daily_report');
    }
}
