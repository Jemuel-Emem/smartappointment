<?php

namespace App\Livewire\Admin;

use App\Models\Requirement;
use Livewire\Component;

class Requirements extends Component
{
    public $requirements;
    public $requirementId;
    public $name;
    public $service;
    public $showModal = false;
    public $isEdit = false;

    public function mount()
    {
        $this->loadRequirements();
    }

    public function loadRequirements()
    {
        $departmentId = \App\Models\Department::where('user_id', auth()->id())->value('id');
        $this->requirements = Requirement::where('department_id', $departmentId)->get();
    }

    public function openModal($id = null)
    {
        $this->reset(['requirementId', 'name', 'service', 'isEdit']);
        if ($id) {
            $req = Requirement::findOrFail($id);
            $this->requirementId = $req->id;
            $this->name = $req->name;
            $this->service = $req->service;
            $this->isEdit = true;
        }
        $this->showModal = true;
    }

    public function save()
    {
        $departmentId = \App\Models\Department::where('user_id', auth()->id())->value('id');

        $this->validate([
            'name' => 'required|string|max:255',
            'service' => 'required|string|max:255', // validate service
        ]);

        Requirement::updateOrCreate(
            ['id' => $this->requirementId],
            [
                'department_id' => $departmentId,
                'name' => $this->name,
                'service' => $this->service,
            ]
        );

        $this->showModal = false;
        $this->loadRequirements();
    }

    public function delete($id)
    {
        Requirement::findOrFail($id)->delete();
        $this->loadRequirements();
    }

    public function render()
    {
        return view('livewire.admin.requirements');
    }
}
