<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $language = Auth::check() ? strtolower(Auth::user()->language) : 'english';


        $translations = [
            'title' => 'Welcome to <span class="text-blue-600">SMART Appointment System</span>',
            'subtitle' => 'Streamline your scheduling process with ease. appoint, manage, and track appointments in one smart platform — anytime, anywhere.',
            'button' => 'APPOINT NOW!',
        ];

        if ($language === 'tagalog') {
            $translations = [
                'title' => 'Maligayang Pagdating sa <span class="text-blue-600">SMART Appointment System</span>',
                'subtitle' => 'Mas pinadali ang iyong pag-iskedyul. Mag-appoint, mag-manage, at subaybayan ang mga appointment sa isang smart na plataporma — kahit saan, kahit kailan.',
                'button' => 'MAG-APPOINT NA!',
            ];
        }

        if ($language === 'bisaya') {
            $translations = [
                'title' => 'Maayong Pag-abot sa <span class="text-blue-600">SMART Appointment System</span>',
                'subtitle' => 'Hapsay nga pag-iskedyul. Mag-appoint, magdumala, ug mosubay sa mga appointment sa usa ka smart nga plataporma — bisan asa, bisan kanus-a.',
                'button' => 'APPOINT NA KARON!',
            ];
        }

        return view('livewire.user.index', compact('translations'));
    }
}
