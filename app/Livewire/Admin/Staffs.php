<?php
namespace App\Livewire\Admin;
use App\Models\Requirement;
use App\Models\Department;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Staffs extends Component
{
    public $name, $address, $phone_number, $speciality, $service, $requirement;
    public $showModal = false;
    public $staffIdBeingEdited = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'speciality' => 'required|string|max:255',
         'service' => 'required|string|max:255',
    ];

    public function openModal()
    {
        $this->resetForm();
        $this->staffIdBeingEdited = null;  // reset edit id
        $this->showModal = true;
    }

    public function editStaff($id)
    {
        $staff = Staff::findOrFail($id);
        $this->staffIdBeingEdited = $staff->id;
        $this->name = $staff->name;
        $this->address = $staff->address;
        $this->phone_number = $staff->phone_number;
        $this->speciality = $staff->speciality;
          $this->service = $staff->service_type;
        $this->showModal = true;
    }
public function toggleAvailability($id)
{
    $staff = Staff::findOrFail($id);
    $staff->availability = !$staff->availability;
    $staff->save();

    session()->flash('message', 'Staff availability updated.');
}

    public function saveStaff()
    {
        $this->validate();

        $department = Department::where('user_id', Auth::id())->first();
        if (!$department) {
            session()->flash('error', 'You have no department assigned.');
            return;
        }

        if ($this->staffIdBeingEdited) {

            $staff = Staff::findOrFail($this->staffIdBeingEdited);
            $staff->update([
                'name' => $this->name,
                'address' => $this->address,
                'phone_number' => $this->phone_number,
                'speciality' => $this->speciality,
                'service_type' => $this->service,
            ]);
            session()->flash('message', 'Staff updated successfully.');
        } else {

            Staff::create([
                'department_id' => $department->id,
                'name' => $this->name,
                'address' => $this->address,
                'phone_number' => $this->phone_number,
                'speciality' => $this->speciality,
                'service_type' => $this->service,
            ]);

             $departmentId = \App\Models\Department::where('user_id', auth()->id())->value('id');

        $this->validate([
            'name' => 'required|string|max:255',
            'service' => 'required|string|max:255',
        ]);

        Requirement::updateOrCreate(

            [
                'department_id' => $departmentId,
                'name' => $this->requirement,
                'service' => $this->service,
            ]
        );
            session()->flash('message', 'Staff added successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteStaff($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
        session()->flash('message', 'Staff deleted successfully.');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->address = '';
        $this->phone_number = '';
        $this->speciality = '';
        $this->staffIdBeingEdited = null;
    }

    public function render()
    {
        $department = Department::where('user_id', Auth::id())->first();
        $staffList = $department ? Staff::where('department_id', $department->id)->get() : collect();

        return view('livewire.admin.staffs', [
            'staffList' => $staffList
        ]);
    }
}
