<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AvailabilityActionModel extends Component
{
    public $title;
    public $options;

    public function __construct($title = 'Modify',$options = [])
    {
        $this->title = $title;
        $this->options = $options;
    }

    public function render()
    {
        return view('components.availability-action-model');
    }
}
