<?php

namespace App\Livewire\Sp;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Staff;
use App\Models\Staff_Rating;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $topStaff;
    public $highDemandServices;
    public $appointmentStats;
    public $departmentSatisfaction;

    public function mount()
    {
        // Top-rated staff (average rating)
        $this->topStaff = Staff::select('staff.id', 'staff.name', DB::raw('AVG(staff__ratings.rating) as avg_rating'))
            ->join('staff__ratings', 'staff.id', '=', 'staff__ratings.staff_id')
            ->groupBy('staff.id', 'staff.name')
            ->orderByDesc('avg_rating')
            ->take(5)
            ->get();

        // High demand services (count of appointments per service_type)
        $this->highDemandServices = Staff::select('staff.service_type', DB::raw('COUNT(appointments.id) as total'))
            ->join('appointments', 'staff.id', '=', 'appointments.staff_id')
            ->groupBy('staff.service_type')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Appointment stats (count grouped by day)
        $this->appointmentStats = Appointment::select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Department satisfaction (average rating per department)
        $this->departmentSatisfaction = Department::select('departments.department_name', DB::raw('AVG(staff__ratings.rating) as avg_rating'))
            ->join('staff', 'departments.id', '=', 'staff.department_id')
            ->join('staff__ratings', 'staff.id', '=', 'staff__ratings.staff_id')
            ->groupBy('departments.id', 'departments.department_name')
            ->orderByDesc('avg_rating')
            ->get();
    }

    public function render()
    {
        return view('livewire.sp.index', [
            'topStaff' => $this->topStaff,
            'highDemandServices' => $this->highDemandServices,
            'appointmentStats' => $this->appointmentStats,
            'departmentSatisfaction' => $this->departmentSatisfaction,
        ]);
    }
}
