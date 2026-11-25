<?php

namespace App\Livewire\User;
use Livewire\Component;

class OrderSuccessPage extends Component
{
    public function render()
    {
        return view('livewire.user.order-success-page')->layout('layouts.user');
    }
}