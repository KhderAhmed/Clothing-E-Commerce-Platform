<?php

namespace App\Livewire;

use Livewire\Component;

abstract class BaseComponent extends Component
{
    /**
     * ترجع اسم الـ layout حسب دور المستخدم
     */
    protected function getLayout(): string
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return 'layouts.admin';
        }

        return 'layouts.user';
    }

    public function render()
    {
        return view($this->view())->layout($this->getLayout());
    }

    abstract protected function view(): string;
}