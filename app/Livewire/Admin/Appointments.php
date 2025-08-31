<?php

namespace App\Livewire\Admin;
use App\Models\Department;
use App\Models\Appointment;
use App\Models\Staff;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentStatusMail;
use Livewire\Component;

class Appointments extends Component
{
    public $appointments;
public $showRescheduleModal = false;
public $rescheduleAppointmentId;
public $new_date;
public $new_time;

public function mount()
{
    $appointments = Appointment::with(['user', 'staff'])
        ->where('department_id', auth()->id())
        ->get();

    $this->appointments = $appointments;
}

public function openReschedule($id)
{
    $this->rescheduleAppointmentId = $id;
    $appointment = Appointment::find($id);

    $this->new_date = $appointment->appointment_date;
    $this->new_time = $appointment->appointment_time;

    $this->showRescheduleModal = true;
}

public function saveReschedule()
{
    $this->validate([
        'new_date' => 'required|date|after_or_equal:today',
        'new_time' => 'required',
    ]);

  $appointment = Appointment::find($this->rescheduleAppointmentId);

    $appointment->update([
        'appointment_date' => $this->new_date,
        'appointment_time' => $this->new_time,
        'status' => 'approved',
    ]);


    Mail::to($appointment->user->email)->send(
        new AppointmentStatusMail($appointment, 'rescheduled')
    );

         $this->sendSMS(
            $appointment->user->phone_number,
            "Your appointment has been RESCHEDULED to {$this->new_date} at {$this->new_time}."
        );

    $this->showRescheduleModal = false;
    $this->rescheduleAppointmentId = null;
    $this->new_date = null;
    $this->new_time = null;

    $this->refreshAppointments();

    session()->flash('success', 'Appointment rescheduled successfully.');
}

public function complete($id)
{
    Appointment::where('id', $id)->update(['status' => 'completed']);
    $this->refreshAppointments();
}


    public function approve($id)
    {
      $appointment = Appointment::find($id);
    $appointment->update(['status' => 'approved']);


    Mail::to($appointment->user->email)->send(
        new AppointmentStatusMail($appointment, 'approved')
    );

      $this->sendSMS(
            $appointment->user->phone_number,
            "Your appointment on {$appointment->appointment_date} at {$appointment->appointment_time} has been APPROVED."
        );

        $this->refreshAppointments();
    }

    public function decline($id)
    {
        $appointment = Appointment::find($id);
    $appointment->update(['status' => 'declined']);


    Mail::to($appointment->user->email)->send(
        new AppointmentStatusMail($appointment, 'declined')
    );

      $this->sendSMS(
            $appointment->user->phone_number,
            "We’re sorry, but your appointment on {$appointment->appointment_date} at {$appointment->appointment_time} has been DECLINED."
        );
        $this->refreshAppointments();
    }

    protected function refreshAppointments()
    {
        $departmentId = auth()->user()->department_id;
        $staffIds = \App\Models\Staff::where('department_id', $departmentId)->pluck('id');

        $this->appointments = Appointment::where('department_id', $departmentId)
            ->whereIn('staff_id', $staffIds)
            ->get();
    }


      private function sendSMS($phoneNumber, $message)
    {
        $ch = curl_init();

        $parameters = [
            'apikey' => '046125f45f4f187e838905df98273c4e', // replace with your Semaphore API key
            'number' => $phoneNumber,
            'message' => $message,
            'sendername' => 'KaisFrozen'
        ];

        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
    public function render()
    {
        return view('livewire.admin.appointments');
    }
}
