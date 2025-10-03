<?php

namespace App\Livewire\Sp;

use App\Models\Staff_Rating;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Comments extends Component
{
    public $summary;
    public $comments;

    public function mount()
    {

        $this->summary = Staff_Rating::select(
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as total_feedbacks')
            )
            ->first();


        $this->comments = Staff_Rating::with('staff', 'user')
            ->whereNotNull('comment')
            ->latest()
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.sp.comments', [
            'summary' => $this->summary,
            'comments' => $this->comments,
        ]);
    }
}
