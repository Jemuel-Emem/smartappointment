<?php

namespace App\Livewire\Admin;
use App\Models\Requirement;
use App\Models\AppointmentLimit;
use App\Models\Department;
use App\Models\Appointment;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentStatusMail;

use Livewire\Component;

class Appointments extends Component
{
    public $activeTab = 'pending';

    public $appointments;
public $showRescheduleModal = false;
public $rescheduleAppointmentId;
public $new_date;
public $new_time;
public $department_id, $limit, $limit_date;
public $timeslot = 'full';

public function mount()
{
    $appointments = Appointment::with(['user', 'staff'])
        ->where('department_id', auth()->id())
        ->get();

    $this->limit = AppointmentLimit::where('user_id', auth()->id())->value('limit') ?? 0;

    $this->appointments = $appointments;
}

public function openReschedule($id)
{
    $this->rescheduleAppointmentId = $id;

    $appointment = Appointment::find($id);
    if (!$appointment) return;

    $this->new_date = $appointment->appointment_date;
    $this->new_time = $appointment->appointment_time;

    $this->showRescheduleModal = true;
}


public function saveLimit()
{
    $this->validate([
        'limit' => 'required|integer|min:1',
        'timeslot' => 'required|in:full,morning,afternoon',
        'limit_date' => 'required|date|after_or_equal:today',
    ]);

    AppointmentLimit::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'limit_date' => $this->limit_date,
        ],
        [
            'limit' => $this->limit,
            'timeslot' => $this->timeslot,
        ]
    );

    flash()->success('Limit updated successfully for ' . $this->limit_date);
}

public function saveReschedule()
{
  $this->validate([
        'new_date' => [
            'required',
            'date',
            'after_or_equal:today',
            function ($attribute, $value, $fail) {
                $dayOfWeek = Carbon::parse($value)->dayOfWeek;

                if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                    $fail('Appointments are not available on weekends.');
                }
            },
        ],
        'new_time' => 'required',
    ]);
  $appointment = Appointment::find($this->rescheduleAppointmentId);

    if (!$appointment) {
        session()->flash('error', 'Appointment not found.');
        return;
    }


    $slot = Carbon::createFromFormat(
        'Y-m-d H:i',
        $this->new_date . ' ' . $this->new_time
    );


    if ($slot->lte(Carbon::now())) {
        session()->flash('error', 'You cannot reschedule to a past time.');
        return;
    }


    $conflict = Appointment::where('staff_id', $appointment->staff_id)
        ->where('appointment_date', $this->new_date)
        ->where('appointment_time', $this->new_time)
        ->where('status', 'approved')
        ->where('id', '!=', $appointment->id)
        ->exists();

    if ($conflict) {
        session()->flash('error', 'Sorry, this time slot is already booked.');
        return;
    }

    $appointment->update([
        'appointment_date' => $this->new_date,
        'appointment_time' => $this->new_time,
        'status' => 'approved',
    ]);


    Mail::to($appointment->user->email)->send(
        new AppointmentStatusMail($appointment, 'rescheduled')
    );

    $formattedTime = Carbon::createFromFormat('H:i', $this->new_time)->format('g:i A');

$this->sendSMS(
    $appointment->user->phone_number,
    "Your appointment has been RESCHEDULED to {$this->new_date} at {$formattedTime}."
);

if ($appointment->staff && $appointment->staff->phone_number) {
    $this->sendSMS(
        $appointment->staff->phone_number,
        "Appointment with {$appointment->user->name} has been RESCHEDULED to {$this->new_date} at {$formattedTime}."
    );
}


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

    $appointment = Appointment::with(['user', 'staff'])->find($id);

    if (!$appointment) {
        session()->flash('error', 'Appointment not found.');
        return;
    }

    $appointment->update(['status' => 'approved']);


    if ($appointment->staff) {
        $requirements = Requirement::where('department_id', $appointment->staff->department_id)
            ->where('service', $appointment->staff->service_type)
            ->pluck('name')
            ->toArray();
    } else {

        $requirements = Requirement::where('department_id', $appointment->department_id)
            ->pluck('name')
            ->toArray();
    }

    $requirementsList = !empty($requirements)
        ? "\nRequirements:\n- " . implode("\n- ", $requirements)
        : "\n(No additional requirements for this appointment.)";


    Mail::to($appointment->user->email)->send(
        new AppointmentStatusMail($appointment, 'approved', $requirementsList)
    );


    $formattedDate = Carbon::parse($appointment->appointment_date)->format('F j, Y');
$formattedTime = Carbon::parse($this->new_time)->format('g:i A');
$formattedTime = Carbon::parse($appointment->appointment_time)->format('g:i A');



    $this->sendSMS(
        $appointment->user->phone_number,
        "Your appointment on {$formattedDate} at {$formattedTime} has been APPROVED." . $requirementsList
    );


    if ($appointment->staff && $appointment->staff->phone_number) {
        $this->sendSMS(
            $appointment->staff->phone_number,
            "New approved appointment: {$appointment->user->firstname} {$appointment->user->lastname} on {$formattedDate} at {$formattedTime}."
        );
    }

    $this->refreshAppointments();
}

// public function approve($id)
// {
//     $appointment = Appointment::with('staff')->find($id);
//     $appointment->update(['status' => 'approved']);

// $requirements = Requirement::where('department_id', $appointment->staff->department_id)
//     ->where('service', $appointment->staff->service_type)
//     ->pluck('name')
//     ->toArray();


//     $requirementsList = !empty($requirements)
//         ? "\nRequirements:\n- " . implode("\n- ", $requirements)
//         : "\n(No additional requirements for this appointment.)";


//     Mail::to($appointment->user->email)->send(
//         new AppointmentStatusMail($appointment, 'approved', $requirementsList)
//     );


// $formattedDate = Carbon::parse($appointment->appointment_date)->format('F j, Y');
// $formattedTime = Carbon::createFromFormat('H:i', $appointment->appointment_time)->format('g:i A');


// $this->sendSMS(
//     $appointment->user->phone_number,
//     "Your appointment on {$formattedDate} at {$formattedTime} has been APPROVED." .
//     $requirementsList
// );


// if ($appointment->staff && $appointment->staff->phone_number) {
//     $this->sendSMS(
//         $appointment->staff->phone_number,
//         "New approved appointment: {$appointment->user->name} on {$formattedDate} at {$formattedTime}."
//     );

//     }
//     $this->refreshAppointments();
// }

    public function decline($id)
    {
        $appointment = Appointment::find($id);
    $appointment->update(['status' => 'declined']);


    Mail::to($appointment->user->email)->send(
        new AppointmentStatusMail($appointment, 'declined')
    );

   $formattedDate = Carbon::parse($appointment->appointment_date)->format('F j, Y');
$formattedTime = Carbon::createFromFormat('H:i', $appointment->appointment_time)->format('g:i A');


$this->sendSMS(
    $appointment->user->phone_number,
    "We’re sorry, but your appointment on {$formattedDate} at {$formattedTime} has been DECLINED."
);


if ($appointment->staff && $appointment->staff->phone_number) {
    $this->sendSMS(
        $appointment->staff->phone_number,
        "Appointment with {$appointment->user->name} on {$formattedDate} at {$formattedTime} has been DECLINED."
    );
}

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
            'apikey' => '046125f45f4f187e838905df98273c4e',
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


public function getAppointmentBeingRescheduled()
{
    if (! $this->rescheduleAppointmentId) {
        return null;
    }

    return Appointment::with('staff', 'department')
        ->find($this->rescheduleAppointmentId);
}

 public function render()
{
    return view('livewire.admin.appointments', [
        'currentLimit' => $this->limit,
        'appointmentBeingRescheduled' => $this->getAppointmentBeingRescheduled(),
    ]);
}



}
