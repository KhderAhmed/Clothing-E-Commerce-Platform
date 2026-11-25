<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    
    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|same:password_confirmation',
    ];
    
    protected $messages = [
        'name.required' => 'الاسم مطلوب',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'أدخل بريداً إلكترونياً صحيحاً',
        'email.unique' => 'هذا البريد مسجل مسبقاً',
        'password.required' => 'كلمة المرور مطلوبة',
        'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        'password.same' => 'كلمة المرور غير متطابقة',
    ];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function register()
    {
        $this->validate();
        
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'user', 
        ]);
        
  
        Auth::login($user);
        
        session()->flash('message', 'تم إنشاء الحساب بنجاح!');
        
        return redirect()->to('/home');
    }
    
    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}