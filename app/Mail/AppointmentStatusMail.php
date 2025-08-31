<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $statusMessage;

    public function __construct(Appointment $appointment, $statusMessage)
    {
        $this->appointment = $appointment;
        $this->statusMessage = $statusMessage;
    }

    public function build()
    {
        return $this->subject('Your Appointment Update')
            ->view('emails.appointment-status')
            ->with([
                'appointment' => $this->appointment,
                'statusMessage' => $this->statusMessage,
            ]);
    }
}
