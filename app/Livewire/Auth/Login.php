<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];
    
    protected $messages = [
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'أدخل بريداً إلكترونياً صحيحاً',
        'password.required' => 'كلمة المرور مطلوبة',
        'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
    ];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function login()
    {
        $this->validate();
        
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];
        
        if (Auth::attempt($credentials, $this->remember)) {
            // تسجيل الدخول ناجح
            session()->regenerate();
            
            $user = Auth::user();
            
            // توجيه حسب الدور
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            
            return redirect()->intended('/home');
        }
        
        // فشل تسجيل الدخول
        $this->addError('email', 'البريد الإلكتروني أو كلمة المرور غير صحيحة');
    }
    
    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}