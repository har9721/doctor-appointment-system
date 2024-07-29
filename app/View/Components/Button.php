<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $color,$size,$textColor;

    public function __construct($color="warning",$size = 'sm',$textColor)
    {
        $this->color = $color;
        $this->size = $size;
        $this->textColor = $textColor;
    }

    public function render()
    {
        return view('components.button');
    }
}
