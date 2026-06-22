<?php

namespace App\View\Components\Buttons;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ActionGroupComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public $url;
    public $type;
    public function __construct($url, $type)
    {
        $this->url = $url;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buttons.action-group-component');
    }
}
