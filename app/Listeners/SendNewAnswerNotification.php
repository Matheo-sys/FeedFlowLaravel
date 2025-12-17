<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAnswerMail;
use App\Models\Survey;
use App\Models\User;
use App\Events\SurveyAnswerSubmitted;

class SendNewAnswerNotification implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(SurveyAnswerSubmitted $event): void
    {
        // Check if the survey owner wants to receive notifications
        if ($event->survey->receive_new_answer_notifications) {
            $user = User::find($event->survey->user_id);
            
            if ($user && $user->email) {
                Mail::to($user->email)->send(
                    new NewAnswerMail($event->survey)
                );
            }
        }
    }
}
