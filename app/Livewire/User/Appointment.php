<?php
namespace App\Livewire\User;

use App\Models\Staff_Rating;
use App\Models\Appointment as A;
use App\Models\Department;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffAppointmentNotification;

use App\Models\Staff;
use Livewire\Attributes\On;
use Carbon\Carbon;

use Livewire\Component;

class Appointment extends Component
{
    public $departments;
    public $department_id;
    public $purpose_of_appointment;
    public $schedule;
    public $suggestedStaff = [];
    public $showStaffList = false;
    public $selectedStaffId;
    public $appointment_date;
    public $appointment_time;

    public $language = 'en'; // default language

    protected $listeners = ['appointmentDateSelected' => 'setAppointmentDate'];

    public $translations = [
        'en' => [
            'book_title' => 'Book an Appointment',
            'select_department' => 'Select Department',
            'choose_department' => '-- Choose Department --',
            'purpose_label' => 'Purpose of Appointment',
            'purpose_placeholder' => "Describe your needs (e.g. 'dental checkup', 'heart consultation')",
            'find_staff' => 'Find Suitable Staff',
            'recommended' => 'Recommended Specialists:',
            'appointment_date' => 'Appointment Date',
            'appointment_time' => 'Appointment Time',
            'select_time' => '-- Select Time --',
            'submit' => 'Submit Appointment',
        ],
        'tl' => [
            'book_title' => 'Mag-iskedyul ng Appointment',
            'select_department' => 'Pumili ng Departamento',
            'choose_department' => '-- Piliin ang Departamento --',
            'purpose_label' => 'Layunin ng Appointment',
            'purpose_placeholder' => "Ilagay ang iyong pakay (hal. 'pagsusuri sa ngipin', 'konsultasyon sa puso')",
            'find_staff' => 'Maghanap ng Angkop na Tauhan',
            'recommended' => 'Mga Inirerekomendang Espesyalista:',
            'appointment_date' => 'Petsa ng Appointment',
            'appointment_time' => 'Oras ng Appointment',
            'select_time' => '-- Piliin ang Oras --',
            'submit' => 'Isumite ang Appointment',
        ],
        'bs' => [
            'book_title' => 'Mag-book og Appointment',
            'select_department' => 'Pili ug Departamento',
            'choose_department' => '-- Pili-a ang Departamento --',
            'purpose_label' => 'Tumong sa Appointment',
            'purpose_placeholder' => "Ibutang ang imong tuyo (pananglitan: 'dental checkup', 'konsultasyon sa kasing-kasing')",
            'find_staff' => 'Pangitaa ang angay nga Staff',
            'recommended' => 'Girekomenda nga mga Espesyalista:',
            'appointment_date' => 'Petsa sa Appointment',
            'appointment_time' => 'Oras sa Appointment',
            'select_time' => '-- Pili-a ang Oras --',
            'submit' => 'Isumite ang Appointment',
        ],
    ];
    public function updatedPurposeOfAppointment($value)
    {
        // Show staff list if purpose is not empty
        if (!empty($value)) {
            $this->showSuggestedStaff();
        } else {
            $this->showStaffList = false;
            $this->suggestedStaff = [];
            $this->selectedStaffId = null;
        }
    }

    public function updatedDepartmentId($value)
    {
        // If purpose is not empty and department changes, update staff list
        if (!empty($this->purpose_of_appointment)) {
            $this->showSuggestedStaff();
        }
    }
    public function setAppointmentDate($date)
    {
        $this->appointment_date = $date;
    }

    public function updated($propertyName)
{
    if (in_array($propertyName, ['purpose_of_appointment', 'department_id'])) {
        if (!empty($this->purpose_of_appointment)) {
            $this->showSuggestedStaff();
        } else {
            $this->showStaffList = false;
            $this->suggestedStaff = [];
            $this->selectedStaffId = null;
        }
    }
}


    public function mount()
    {
        $this->departments = Department::all();
    }

    public function showSuggestedStaff()
    {
        $this->showStaffList = false;
        $this->suggestedStaff = [];
        $this->selectedStaffId = null;

        if (empty($this->purpose_of_appointment)) {
            session()->flash('error', 'Please enter purpose to get suggestions.');
            return;
        }

        $keywords = $this->extractKeywords($this->purpose_of_appointment);

        $query = Staff::query();

        if ($this->department_id) {
            $query->where('department_id', $this->department_id);
        }

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $keyword) {
                $q->orWhere('speciality', 'like', '%' . $keyword . '%')
                  ->orWhere('service_type', 'like', '%' . $keyword . '%');
            }
        });

       $this->suggestedStaff = $query
    ->withAvg('ratings', 'rating')
    ->get();

        $this->showStaffList = true;
    }

    private function extractKeywords($purpose)
    {
        $stopWords = ['the', 'and', 'for', 'with', 'about', 'my', 'to', 'of', 'a', 'an', 'on'];
        $words = str_word_count(strtolower($purpose), 1);

        return array_filter(array_unique($words), function ($word) use ($stopWords) {
            return strlen($word) > 3 && !in_array($word, $stopWords);
        });
    }

    public function selectStaff($staffId)
    {
        $this->selectedStaffId = $staffId;
    }


//     public function submit()
// {
//     $this->validate([
//         'purpose_of_appointment' => 'required|string|max:255',
//         'appointment_date' => 'required|date|after_or_equal:today',
//         'appointment_time' => 'required|date_format:H:i',
//         'selectedStaffId' => 'required|exists:staff,id',
//     ]);


//     $conflict = A::where('staff_id', $this->selectedStaffId)
//         ->where('appointment_date', $this->appointment_date)
//         ->where('appointment_time', $this->appointment_time)
//         ->where('status', 'approved')
//         ->exists();

//     if ($conflict) {
//         session()->flash('error', 'Sorry, this time slot is already booked ');
//         return;
//     }

//     $department = Department::findOrFail($this->department_id);

//     // A::create([
//     //     'user_id' => auth()->id(),
//     //     'department_id' => $department->user_id,
//     //     'staff_id' => $this->selectedStaffId,
//     //     'purpose_of_appointment' => $this->purpose_of_appointment,
//     //     'appointment_date' => $this->appointment_date,
//     //     'appointment_time' => $this->appointment_time,
//     //     'status' => 'pending',

//        $appointment = A::create([
//         'user_id' => auth()->id(),
//         'department_id' => $department->user_id,
//         'staff_id' => $this->selectedStaffId,
//         'purpose_of_appointment' => $this->purpose_of_appointment,
//         'appointment_date' => $this->appointment_date,
//         'appointment_time' => $this->appointment_time,
//         'status' => 'pending',
//     ]);


//   $staff = Staff::find($this->selectedStaffId);
//     if ($staff && $staff->email) {
//         Mail::to($staff->email)->send(new StaffAppointmentNotification($appointment));
//     }

//     $this->reset([
//         'department_id',
//         'purpose_of_appointment',
//         'schedule',
//         'selectedStaffId',
//         'showStaffList',
//         'suggestedStaff'
//     ]);

//     session()->flash('success', 'Appointment submitted successfully! Waiting for approval.');
// }




public function submit()
{


    $this->validate([
        'purpose_of_appointment' => 'required|string|max:255',
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required|date_format:H:i',
        'selectedStaffId' => 'required|exists:staff,id',
    ]);

$slot = Carbon::createFromFormat('Y-m-d H:i', $this->appointment_date . ' ' . $this->appointment_time);

if ($slot->lte(Carbon::now())) {
    session()->flash('error', 'You cannot book an appointment in the past. Please choose a future time.');
    return;
}

    // Prevent booking in the past
    if ($slot->lte(Carbon::now())) {
        session()->flash('error', 'You cannot book an appointment in the past. Please choose a future time.');
        return;
    }

    // Check conflict
    $conflict = A::where('staff_id', $this->selectedStaffId)
        ->where('appointment_date', $this->appointment_date)
        ->where('appointment_time', $this->appointment_time)
        ->where('status', 'approved')
        ->exists();

    if ($conflict) {
        session()->flash('error', 'Sorry, this time slot is already booked.');
        return;
    }

    $department = Department::findOrFail($this->department_id);

    $appointment = A::create([
        'user_id' => auth()->id(),
        'department_id' => $department->user_id,
        'staff_id' => $this->selectedStaffId,
        'purpose_of_appointment' => $this->purpose_of_appointment,
        'appointment_date' => $this->appointment_date,
        'appointment_time' => $this->appointment_time,
        'status' => 'pending',
    ]);

    $staff = Staff::find($this->selectedStaffId);
    if ($staff && $staff->email) {
        Mail::to($staff->email)->send(new StaffAppointmentNotification($appointment));
    }

    $this->reset([
        'department_id',
        'purpose_of_appointment',
        'schedule',
        'selectedStaffId',
        'showStaffList',
        'suggestedStaff'
    ]);

    session()->flash('success', 'Appointment submitted successfully! Waiting for approval.');
}


    public function render()
    {
        return view('livewire.user.appointment');
    }
}
