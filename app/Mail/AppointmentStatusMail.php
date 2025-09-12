<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $status;            // 'approved', 'declined', 'rescheduled', etc.
    public $requirementsList;  // optional string with bullet list
    public $statusMessage;     // human-friendly text for the blade

    public function __construct($appointment, $status, $requirementsList = null)
    {
        $this->appointment      = $appointment;
        $this->status           = $status;
        $this->requirementsList = $requirementsList;

        // Map to a friendly phrase if you want
        $map = [
            'approved'    => 'approved',
            'declined'    => 'declined',
            'rescheduled' => 'rescheduled',
            'completed'   => 'marked as completed',
            'pending'     => 'marked as pending',
        ];
        $this->statusMessage = $map[strtolower($status)] ?? $status;
    }

    public function build()
    {
        return $this->subject('Appointment ' . ucfirst($this->status))
            ->view('emails.appointment-status')
            ->with([
                'appointment'      => $this->appointment,
                'statusMessage'    => $this->statusMessage,
                'requirementsList' => $this->requirementsList,
            ]);
    }
}
