<?php

namespace App\Livewire\User;

use App\Models\Staff_Rating;
use App\Models\Appointment;
use Carbon\Carbon;
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
    public $comment;
    public $language;

    public function mount()
    {
        $this->language = Auth::user()->language ?? 'English';
        $this->refreshAppointments();
    }

    public function cancel($id)
    {
        $appointment = Appointment::where('user_id', Auth::id())->findOrFail($id);
        $appointment->update(['status' => 'cancelled']);

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

    // public function reschedule()
    // {
    //     if (!$this->selectedAppointment) return;

    //     $this->validate([
    //         'rescheduleDate' => 'required|date|after_or_equal:today',
    //         'rescheduleTime' => 'required',
    //     ]);

    //     $slot = Carbon::createFromFormat('Y-m-d H:i', $this->rescheduleDate . ' ' . $this->rescheduleTime);

    //     // 🕒 Prevent selecting past time
    //     if ($slot->lte(Carbon::now())) {
    //         session()->flash('error', 'You cannot reschedule to a past time.');
    //         return;
    //     }

    //     // 🚫 Prevent double booking for same staff (or department)
    //     $conflict = Appointment::where('staff_id', $this->selectedAppointment->staff_id)
    //         ->where('appointment_date', $this->rescheduleDate)
    //         ->where('appointment_time', $this->rescheduleTime)
    //         ->where('status', '!=', 'cancelled')
    //         ->where('id', '!=', $this->selectedAppointment->id)
    //         ->exists();

    //     if ($conflict) {
    //         session()->flash('error', 'Sorry, this time slot is already booked.');
    //         return;
    //     }

    //     // ✅ Update appointment
    //     $this->selectedAppointment->update([
    //         'appointment_date' => $this->rescheduleDate,
    //         'appointment_time' => $this->rescheduleTime,
    //         'status' => 'pending',
    //     ]);

    //     $this->showRescheduleModal = false;
    //     $this->refreshAppointments();
    //     session()->flash('message', 'Appointment rescheduled successfully.');
    // }

    public function reschedule()
{



    if (!$this->selectedAppointment) return;

    $this->validate([
        'rescheduleDate' => 'required|date|after_or_equal:today',
        'rescheduleTime' => 'required',
    ]);

    $slot = Carbon::createFromFormat('Y-m-d H:i', $this->rescheduleDate . ' ' . $this->rescheduleTime);

   if ($slot->isWeekend()) {
        session()->flash('error', 'You cannot reschedule on Saturday or Sunday.');
        return;
    }

    if ($slot->lte(Carbon::now())) {
        session()->flash('error', 'You cannot reschedule to a past time.');
        return;
    }


    $staffId = $this->selectedAppointment->staff_id;
    $departmentId = $this->selectedAppointment->department_id;


    $conflict = \App\Models\Appointment::where('staff_id', $staffId)
        ->where('appointment_date', $this->rescheduleDate)
        ->where('appointment_time', $this->rescheduleTime)
        ->where('status', 'approved')
        ->where('id', '!=', $this->selectedAppointment->id)
        ->exists();

    if ($conflict) {
        session()->flash('error', 'Sorry, this time slot is already booked.');
        return;
    }


    $department = \App\Models\Department::find($departmentId);
    if ($department) {
        $adminId = $department->user_id;

        $limitRecord = \App\Models\AppointmentLimit::where('user_id', $adminId)
            ->whereDate('limit_date', $this->rescheduleDate)
            ->first();

        if ($limitRecord) {
            $limit = $limitRecord->limit;

            $count = \App\Models\Appointment::where('department_id', $adminId)
                ->whereDate('appointment_date', $this->rescheduleDate)
                ->count();

            if ($count >= $limit) {
                session()->flash('error', 'This department has reached its appointment limit for that day.');
                return;
            }
        }
    }

    $this->selectedAppointment->update([
        'appointment_date' => $this->rescheduleDate,
        'appointment_time' => $this->rescheduleTime,
        'status' => 'pending',
    ]);

    $this->showRescheduleModal = false;
    $this->refreshAppointments();

    session()->flash('message', 'Appointment rescheduled successfully.');
}

    public function openRatingModal($id)
    {
        $this->selectedAppointment = Appointment::where('user_id', Auth::id())->findOrFail($id);
        $this->rating = null;
        $this->comment = null;
        $this->showRatingModal = true;
    }

    public function submitRating()
    {
        if ($this->selectedAppointment && $this->rating >= 1 && $this->rating <= 5) {
            Staff_Rating::updateOrCreate(
                [
                    'staff_id' => $this->selectedAppointment->staff_id,
                    'user_id' => auth()->id(),
                ],
                [
                    'rating' => $this->rating,
                    'comment' => $this->comment,
                ]
            );

            $this->selectedAppointment->update(['rated' => true]);
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
        return view('livewire.user.status', [
            'language' => $this->language,
        ]);
    }
}
