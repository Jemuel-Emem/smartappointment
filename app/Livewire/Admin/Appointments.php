<?php

namespace App\Livewire\Admin;
use App\Models\Department;
use App\Models\Appointment;
use App\Models\Staff;
use Livewire\Component;

class Appointments extends Component
{
    public $appointments;

public function mount()
{
    $appointments = Appointment::with(['user', 'staff'])
        ->where('department_id', auth()->id())
        ->get();

    $this->appointments = $appointments;
}

public function complete($id)
{
    Appointment::where('id', $id)->update(['status' => 'completed']);
    $this->refreshAppointments();
}


    public function approve($id)
    {
        Appointment::where('id', $id)->update(['status' => 'approved']);
        $this->refreshAppointments();
    }

    public function decline($id)
    {
        Appointment::where('id', $id)->update(['status' => 'declined']);
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

    public function render()
    {
        return view('livewire.admin.appointments');
    }
}
