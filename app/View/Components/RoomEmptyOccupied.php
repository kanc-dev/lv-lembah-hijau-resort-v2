<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RoomEmptyOccupied extends Component
{
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
        return view('components.room-empty-occupied', ['branchId' => $this->branchId]);
    }
}
