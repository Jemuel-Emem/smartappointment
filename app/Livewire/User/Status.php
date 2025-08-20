<?php

namespace App\Livewire\User;
use App\Models\Staff_Rating;
use App\Models\Appointment;
use App\Models\Staff;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Status extends Component
{
    public $appointments;
    public $selectedAppointment;
    public $rescheduleDate;
    public $rescheduleTime;
    public $showRescheduleModal = false;

    public $showRatingModal = false;
    public $rating;

    public function mount()
    {
        $this->refreshAppointments();
    }

    public function cancel($id)
    {
        $appointment = Appointment::where('user_id', Auth::id())->findOrFail($id);
        $appointment->status = 'cancelled';
        $appointment->save();

        $this->refreshAppointments();
        session()->flash('message', 'Appointment cancelled successfully.');
    }

    public function openReschedule($id)
    {
        $this->selectedAppointment = Appointment::where('user_id', Auth::id())->findOrFail($id);
        $this->rescheduleDate = $this->selectedAppointment->appointment_date;
        $this->rescheduleTime = $this->selectedAppointment->appointment_time;
        $this->showRescheduleModal = true;
    }

    public function reschedule()
    {
        if ($this->selectedAppointment) {
            $this->selectedAppointment->appointment_date = $this->rescheduleDate;
            $this->selectedAppointment->appointment_time = $this->rescheduleTime;
            $this->selectedAppointment->status = 'pending';
            $this->selectedAppointment->save();
        }

        $this->showRescheduleModal = false;
        $this->refreshAppointments();
        session()->flash('message', 'Appointment rescheduled successfully.');
    }

    public function openRatingModal($id)
    {
        $this->selectedAppointment = Appointment::where('user_id', Auth::id())->findOrFail($id);
        $this->showRatingModal = true;
    }

public function submitRating()
{
    if ($this->selectedAppointment && $this->rating >= 1 && $this->rating <= 5) {
       Staff_Rating::updateOrCreate(
            [
                'staff_id' => $this->selectedAppointment->staff_id,
                'user_id'  => auth()->id(),
            ],
            [
                'rating' => $this->rating,
            ]
        );

        $this->showRatingModal = false;
        $this->refreshAppointments();
        session()->flash('message', 'Thank you for rating the staff!');
    }
}


    private function refreshAppointments()
    {
        $this->appointments = Appointment::where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.user.status');
    }
}
