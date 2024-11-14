<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminBookComponent extends Component
{
    public function __construct()
    {
        
    }

    public function render()
    {
        return view('components.admin-book-appointment');
    }
}
