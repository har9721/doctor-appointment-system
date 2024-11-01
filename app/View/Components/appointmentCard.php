<?php

namespace App\View\Components;

use Illuminate\View\Component;

class appointmentCard extends Component
{
    public $appointment;
    public $status;
    public $to_date;

    public function __construct($appointment,$status,$to_date = null)
    {
        $this->appointment = $appointment;
        $this->status = $status;
        $this->to_date = $to_date;
    }

    public function render()
    {
        return view('components.appointment-card');
    }
}
