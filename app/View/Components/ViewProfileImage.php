<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ViewProfileImage extends Component
{
    public $imageUrl;

    public function __construct($image)
    {
        info($image);
        $this->imageUrl = $image;
    }

    public function render()
    {
        return view('components.view-profile-image');
    }
}
