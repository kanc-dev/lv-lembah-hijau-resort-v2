<?php

namespace App\View\Components\Chart;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BranchGuestChart extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;

    public function __construct($title = 'Default Chart Title')
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chart.branch-guest-chart');
    }
}
