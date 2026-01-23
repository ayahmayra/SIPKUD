<?php

namespace App\Livewire\UserManual;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'User Manual'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.user-manual.index');
    }
}
