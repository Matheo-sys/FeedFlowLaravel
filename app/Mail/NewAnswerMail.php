<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Survey;

class NewAnswerMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Survey $survey)
    {
    }

    public function build(): Mailable
    {
        return $this->subject('New Answer Mail')
                    ->markdown('mail.new-answer-mail')
                    ->with(['survey' => $this->survey]);
    }
}
