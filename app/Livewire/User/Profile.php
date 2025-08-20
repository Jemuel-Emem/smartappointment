<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public $name;
    public $email;
    public $barangay;
    public $language;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->barangay = $user->barangay;
        $this->language = $user->language;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'barangay' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'barangay' => $this->barangay,
            'language' => $this->language,
        ]);

        session()->flash('success', 'Profile updated successfully!');
    }

    public function render()
    {
        return view('livewire.user.profile');
    }
}
