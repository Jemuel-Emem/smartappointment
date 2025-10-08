<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Announcement as A;
use Illuminate\Support\Facades\Auth;

class Announcement extends Component
{
    public $announcements = [];

    public function mount()
    {

        $departmentId = Auth::user()->department->id ?? null;


        $this->announcements = A::where(function ($query) use ($departmentId) {
            $query->where('audience', 'departments')
                  ->orWhere('audience', 'both');

            if ($departmentId) {
                $query->orWhere(function ($sub) use ($departmentId) {
                    $sub->where('audience', 'specific_department')
                        ->where('department_id', $departmentId);
                });
            }
        })
        ->latest()
        ->get();
    }

    public function render()
    {
        return view('livewire.admin.announcement', [
            'announcements' => $this->announcements,
        ]);
    }
}
