<?php

namespace App\Livewire\Sp;

use Livewire\Component;
use App\Models\Announcement as AnnouncementModel;
use App\Models\Department;

class Announcement extends Component
{
    public $title, $message, $audience = 'departments', $department_id;
    public $departments = [];

    public function mount()
    {
        $this->departments = Department::all();
    }

    public function createAnnouncement()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'audience' => 'required|in:departments,users,both,specific_department',
            'department_id' => 'required_if:audience,specific_department'
        ]);

        AnnouncementModel::create([
            'title' => $this->title,
            'message' => $this->message,
            'audience' => $this->audience,
            'department_id' => $this->audience === 'specific_department' ? $this->department_id : null,
        ]);

        $this->reset(['title', 'message', 'audience', 'department_id']);
        session()->flash('success', 'Announcement created successfully!');
          return redirect()->route('sp.confirmation');
    }

    public function render()
    {
        return view('livewire.sp.announcement', [
            'announcements' => AnnouncementModel::latest()->get(),
        ]);
    }
}
