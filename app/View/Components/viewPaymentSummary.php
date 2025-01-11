<?php

namespace App\View\Components;

use Illuminate\View\Component;

class viewPaymentSummary extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('components.view-payment-summary');
    }
}
