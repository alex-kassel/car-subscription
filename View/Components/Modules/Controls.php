<?php

declare(strict_types=1);

namespace CarSubscription\View\Components\Modules;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Controls extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View
    {
        return view('cars::components.modules.controls');
    }
}
