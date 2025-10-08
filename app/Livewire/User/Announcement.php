<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Announcement as A;

class Announcement extends Component
{
    public $announcements = [];

    public function mount()
    {

        $this->announcements = A::whereIn('audience', ['users', 'both'])
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.user.announcement', [
            'announcements' => $this->announcements,
        ]);
    }
}
