<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PatientEditDetail extends Component
{
    public $patientsData;
    public $isHideSaveButton;
    public $class;

    public function __construct($patientsData,$isHideSaveButton,$class)
    {
        $this->patientsData = $patientsData;
        $this->isHideSaveButton = $isHideSaveButton;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.patient-edit-detail');
    }
}
