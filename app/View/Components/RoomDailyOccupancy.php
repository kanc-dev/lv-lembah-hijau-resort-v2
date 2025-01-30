<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RoomDailyOccupancy extends Component
{
    /**
     * Create a new component instance.
     */
    private $branchId;
    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.room-daily-occupancy', ['branchId' => $this->branchId]);
    }
}
