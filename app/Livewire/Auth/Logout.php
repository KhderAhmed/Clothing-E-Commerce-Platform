<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    /**
     * الاستماع لحدث تسجيل الخروج من أي مكان بالتطبيق
     */
    #[On('trigger-logout')]
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect('/login');
    }
    
    public function render()
    {
        // نرجعه فارغاً تماماً لأنه سيعمل كـ "جندي خفي" في الخلفية لخدمة الـ Event
        return <<<'HTML'
        <div class="hidden"></div>
        HTML;
    }
}