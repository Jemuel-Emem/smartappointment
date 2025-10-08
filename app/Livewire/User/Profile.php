<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public $firstname;
    public $lastname;
    public $middlename;
    public $email;
    public $barangay;
    public $language;
    public $lang = [];

    public $password;
public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();

        $this->firstname  = $user->firstname;
        $this->lastname   = $user->lastname;
        $this->middlename = $user->middlename;
        $this->email      = $user->email;
        $this->barangay   = $user->barangay;
        $this->language   = $user->language;

        $translations = [
            'English' => [
                'my_profile'   => 'My Profile',
                'update_info'  => 'Update your account information below',
                'firstname'    => 'First Name',
                'lastname'     => 'Last Name',
                'middlename'   => 'Middle Name',
                'email'        => 'Email',
                'barangay'     => 'Barangay',
                'language'     => 'Language',
                'save_changes' => 'Save Changes',
            ],
            'Tagalog' => [
                'my_profile'   => 'Aking Profile',
                'update_info'  => 'I-update ang iyong impormasyon sa account sa ibaba',
                'firstname'    => 'Pangalan',
                'lastname'     => 'Apelyido',
                'middlename'   => 'Gitnang Pangalan',
                'email'        => 'Email',
                'barangay'     => 'Barangay',
                'language'     => 'Wika',
                'save_changes' => 'I-save ang Pagbabago',
            ],
            'Bisaya' => [
                'my_profile'   => 'Akong Profile',
                'update_info'  => 'I-update ang imong impormasyon sa account sa ubos',
                'firstname'    => 'Unang Ngalan',
                'lastname'     => 'Apelyido',
                'middlename'   => 'Tunga nga Ngalan',
                'email'        => 'Email',
                'barangay'     => 'Barangay',
                'language'     => 'Pinulongan',
                'save_changes' => 'I-save ang Pag-usab',
            ],
        ];

        $this->lang = $translations[$this->language] ?? $translations['English'];
    }

public function updateProfile()
{
    $this->validate([
        'firstname'  => 'required|string|max:255',
        'lastname'   => 'required|string|max:255',
        'middlename' => 'nullable|string|max:255',
        'email'      => 'required|email|max:255|unique:users,email,' . Auth::id(),
        'barangay'   => 'nullable|string|max:255',
        'language'   => 'nullable|string|max:255',
        'password'   => 'nullable|string|min:8|confirmed', // new validation rule
    ]);

    $user = Auth::user();

    $updateData = [
        'firstname'  => $this->firstname,
        'lastname'   => $this->lastname,
        'middlename' => $this->middlename,
        'email'      => $this->email,
        'barangay'   => $this->barangay,
        'language'   => $this->language,
    ];

    if (!empty($this->password)) {
        $updateData['password'] = bcrypt($this->password);
    }

    $user->update($updateData);

    $messages = [
        'English' => 'Profile updated successfully!',
        'Tagalog' => 'Matagumpay na na-update ang profile!',
        'Bisaya'  => 'Malampuson nga na-update ang profile!',
    ];

    session()->flash('success', $messages[$this->language] ?? $messages['English']);
}

    public function render()
    {
        return view('livewire.user.profile', [
            'lang' => $this->lang,
        ]);
    }
}
