<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAnswerNotification;
use App\Mail\NewAnswerMail;

use App\Models\Survey;
use App\Models\User;
use App\Events\SurveyAnswerSubmitted;
use App\Models\User;

class SendNewAnswerNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {

     }

    /**
     * Handle the event.
     */
    public function handle(SurveyAnswerSubmitted $event): void
    {
        $email = User::find($event->survey->user_id)->email;
        Mail::to($email)->send(
            new NewAnswerMail($event->survey));
    }
}
