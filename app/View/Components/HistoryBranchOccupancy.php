<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HistoryBranchOccupancy extends Component
{
    private $branchId;
    /**
     * Create a new component instance.
     */
    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.history-branch-occupancy', ['branchId' => $this->branchId]);
    }
}
