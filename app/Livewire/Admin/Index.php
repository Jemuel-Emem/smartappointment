<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $todayAppointments;
    public $serviceStats;

    public function mount()
    {
        $adminId = Auth::id(); // because appointments.department_id = departments.user_id

        // 📅 Today's total appointments
        $this->todayAppointments = Appointment::where('department_id', $adminId)
            ->whereDate('appointment_date', today())
            ->count();


        $this->serviceStats = Appointment::select('status', DB::raw('COUNT(*) as total'))
            ->where('department_id', $adminId)
            ->groupBy('status')
            ->pluck('total', 'status');


    }

    public function render()
    {
        return view('livewire.admin.index', [
            'todayAppointments' => $this->todayAppointments,
            'serviceStats' => $this->serviceStats,
        ]);
    }
}
