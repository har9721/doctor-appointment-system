<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PatientEditDetail extends Component
{
    public $patientsData;

    public function __construct($patientsData)
    {
        $this->patientsData = $patientsData;
    }

    public function render()
    {
        return view('components.patient-edit-detail');
    }
}
