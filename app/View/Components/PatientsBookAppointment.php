<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PatientsBookAppointment extends Component
{
    public function __construct()
    {

    }

    public function render()
    {
        return view('components.patients-book-appointment');
    }
}
