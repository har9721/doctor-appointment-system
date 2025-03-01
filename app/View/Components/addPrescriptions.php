<?php

namespace App\View\Components;

use Illuminate\View\Component;

class addPrescriptions extends Component
{
    public function __construct()
    {

    }

    public function render()
    {
        return view('components.add-prescriptions');
    }
}
