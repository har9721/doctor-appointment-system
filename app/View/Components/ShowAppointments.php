<?php

namespace App\View\Components;

use App\Traits\Home;
use Illuminate\View\Component;

class ShowAppointments extends Component
{
    use Home;

    public $appointments;

    public function __construct()
    {
        $this->appointments = $this->fetchAppointments();
    }

    public function render()
    {
        return view('components.show-appointments');
    }
}
