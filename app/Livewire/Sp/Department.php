<?php

namespace App\Livewire\Sp;

use App\Models\Department as DepartmentModel;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Department extends Component
{
    public $showModal = false;
    public $departmentId = null;
    public $department_name = '';
    public $service_type = '';
    public $email = '';
    public $password = '';

    protected $rules = [
        'department_name' => 'required|string|max:255',
        'service_type' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ];

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->reset(['departmentId', 'department_name', 'service_type', 'email', 'password']);
        $this->resetValidation();
    }

    public function saveDepartment()
    {
        if ($this->departmentId) {

            $department = DepartmentModel::findOrFail($this->departmentId);

            $department->update([
                'department_name' => $this->department_name,
                'service_type' => $this->service_type,
            ]);

            $user = $department->user;
            $user->update([
                'name' => $this->department_name,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
            ]);

            session()->flash('message', 'Department updated successfully!');
        } else {

            $this->validate();

            $user = User::create([
                'name' => $this->department_name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_admin' => 1,
            ]);

            DepartmentModel::create([
                'user_id' => $user->id,
                'department_name' => $this->department_name,
                'service_type' => $this->service_type,
            ]);

            session()->flash('message', 'Department added successfully!');
        }

        $this->closeModal();
    }

    public function editDepartment($id)
    {
        $dept = DepartmentModel::with('user')->findOrFail($id);

        $this->departmentId = $dept->id;
        $this->department_name = $dept->department_name;
        $this->service_type = $dept->service_type;
        $this->email = $dept->user->email ?? '';
        $this->password = '';

        $this->showModal = true;
    }

    public function deleteDepartment($id)
    {
        $department = DepartmentModel::findOrFail($id);


        if ($department->user) {
            $department->user->delete();
        }

        $department->delete();

        session()->flash('message', 'Department deleted successfully!');
    }

    public function render()
    {
        $departments = DepartmentModel::with('user')->latest()->get();
        return view('livewire.sp.department', compact('departments'));
    }
}
