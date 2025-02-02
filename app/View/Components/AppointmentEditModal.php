<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppointmentEditModal extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.appointment-edit-modal');
    }
}
