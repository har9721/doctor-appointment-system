<?php

namespace App\View\Components;

use App\Traits\Home;
use Illuminate\View\Component;

class AppointmentsCards extends Component
{
    use Home;

    public $dashboardData;

    public function __construct()
    {
        $this->dashboardData = $this->getDashboardData();
    }

    public function render()
    {
        return view('components.appointments-cards');
    }
}
