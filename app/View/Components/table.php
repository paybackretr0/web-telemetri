<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class table extends Component
{
    public $search;
    public $striped;
    public $hover;
    public $data;

    public function __construct($search = null, $striped = true, $hover = true, $data = null)
    {
        $this->search = $search;
        $this->striped = $striped;
        $this->hover = $hover;
        $this->data = $data;
    }

    public function render(): View|Closure|string
    {
        return view('components.table');
    }
}
