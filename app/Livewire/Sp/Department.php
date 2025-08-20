<?php

namespace App\Livewire\Sp;

use App\Models\Department as DepartmentModel;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Department extends Component
{
    public $showModal = false;
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
        $this->reset(['department_name', 'service_type', 'email', 'password']);
        $this->resetValidation();
    }

    public function saveDepartment()
    {
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

        $this->closeModal();
        session()->flash('message', 'Department added successfully!');
    }

    public function render()
    {
        $departments = DepartmentModel::with('user')->latest()->get();
        return view('livewire.sp.department', compact('departments'));
    }
}
