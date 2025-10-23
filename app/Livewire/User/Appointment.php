<?php
namespace App\Livewire\User;

use App\Models\Staff_Rating;
use App\Models\Appointment as A;
use App\Models\Department;
use Illuminate\Support\Facades\Mail;

use App\Models\AppointmentLimit;
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
    public $availableSlots = [];

    public $language = 'en';

    protected $listeners = ['appointmentDateSelected' => 'setAppointmentDate'];

    public $translations = [
        'en' => [
            'book_title' => 'Appointment',
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
            'book_title' => 'Appointment',
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


    public function updatedAppointmentDate($value)
{
    $this->loadAvailableSlots();
}

 private function normalizeLang($lang): string
    {
        $map = [
            'english' => 'en',
            'tagalog' => 'tl',
            'bisaya'  => 'bs',
            'en'      => 'en',
            'tl'      => 'tl',
            'bs'      => 'bs',
        ];
        return $map[strtolower($lang ?? 'en')] ?? 'en';
    }

public $remainingSlots;

public function loadAvailableSlots()
{
    // Reset slots when no department or date
    if (!$this->department_id || !$this->appointment_date) {
        $this->remainingSlots = null;
        return;
    }

    // Find the department
    $department = Department::find($this->department_id);
    if (!$department) {
        $this->remainingSlots = null;
        return;
    }

    $adminId = $department->user_id;

    // ✅ Get appointment limit for this specific date only
    $limitRecord = AppointmentLimit::where('user_id', $adminId)
        ->whereDate('limit_date', $this->appointment_date)
        ->first();

    // If no specific date limit found, fallback to default daily limit
    if (!$limitRecord) {
        $this->remainingSlots = null; // or set default like 0 or 15
        return;
    }

    $limit = $limitRecord->limit;

    // ✅ Count appointments for that same date only
    $count = A::where('department_id', $adminId)
        ->whereDate('appointment_date', $this->appointment_date)
        ->count();

    // ✅ Compute remaining slots for that date only
    $this->remainingSlots = max($limit - $count, 0);
}


    public function updatedPurposeOfAppointment($value)
    {

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

        if (!empty($this->purpose_of_appointment)) {
            $this->showSuggestedStaff();
        }

        $this->loadAvailableSlots();
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

 $user = auth()->user();
        $this->language = $this->normalizeLang($user->language ?? 'en');
}

 public function updatedLanguage($value)
    {
        $this->language = $this->normalizeLang($value);
        $user = auth()->user();
        $user->language = $value;
        $user->save();
    }

    public function showSuggestedStaff()
    {
        $this->showStaffList = false;
        $this->suggestedStaff = [];
        $this->selectedStaffId = null;

        if (empty($this->purpose_of_appointment)) {

              flash()->warning('Please enter purpose to get suggestions.');
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

flash()->warning('You cannot book an appointment in the past. Please choose a future time.');

    return;
}


    if ($slot->lte(Carbon::now())) {

        flash()->warning('You cannot book an appointment in the past. Please choose a future time.');
        return;
    }


    $conflict = A::where('staff_id', $this->selectedStaffId)
        ->where('appointment_date', $this->appointment_date)
        ->where('appointment_time', $this->appointment_time)
        ->where('status', 'approved')
        ->exists();

    if ($conflict) {

          flash()->warning('Sorry, this time slot is already booked.');
        return;
    }

      $department = Department::findOrFail($this->department_id);
    $adminId = $department->user_id;

    $limitRecord = AppointmentLimit::where('user_id', $adminId)->first();

    if ($limitRecord) {
        $appointmentsCount = A::where('department_id', $adminId)
            ->whereDate('appointment_date', $this->appointment_date)
            ->count();

        if ($appointmentsCount >= $limitRecord->limit) {

                 flash()->warning('This department has reached its appointment limit for the day.');
            return;
        }
    }


    $appointmentsCount =A::where('department_id', $this->department_id)
        ->whereDate('appointment_date', $this->appointment_date)
        ->count();

    if ($appointmentsCount >= $limitRecord->limit) {

          flash()->warning('This department has reached its appointment limit for the day.');
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


       flash()->success('Appointment submitted successfully! Waiting for approval.');

}


    public function render()
    {
        return view('livewire.user.appointment', [
            'translations' => $this->translations[$this->language]
        ]);
    }
}
