<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AddAvailabilityModel extends Component
{
    public $id;
    public $weekdays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        return view('components.add-availability-model');
    }
}
