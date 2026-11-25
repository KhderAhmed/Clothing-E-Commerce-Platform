<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Events\ProfileUpdatedEvent;
 use Livewire\Attributes\On;
class ProfileAdmin extends Component
{
    // بيانات المستخدم
    public $name;
    public $email;

    // بيانات كلمة المرور
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    // رسائل مخصصة للتحقق
    protected $messages = [
        'name.required' => 'الاسم مطلوب',
        'name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'أدخل بريداً إلكترونياً صحيحاً',
        'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
        'current_password.required' => 'كلمة المرور الحالية مطلوبة',
        'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة',
        'password.required' => 'كلمة المرور الجديدة مطلوبة',
        'password.min' => 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل',
        'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    // تحديث البيانات الأساسية
    public function updateProfile()
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($validated);

        // 🔥 إطلاق حدث الريل تايم فوراً لـ Reverb
        event(new ProfileUpdatedEvent($user->id, $user->name));

        // إطلاق إشعار محلي للمستخدم الحالي عبر الـ جافاسكريبت المتواجد عندك
        $this->dispatch('showNotification', message: 'تم تحديث البيانات الشخصية بنجاح.', type: 'success');
    }

    // تحديث كلمة المرور
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:6|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('password_success', 'تم تغيير كلمة المرور بنجاح.');
    }
   

#[On('refreshProfile')]
public function refreshProfile()
{
    $user = Auth::user();
    $this->name = $user->name;
    $this->email = $user->email;
}

    public function render()
    {
        // نربطها بالـ layout الافتراضي للموقع بعد تسجيل الدخول
        return view('livewire.admin.profileAdmin')->layout('layouts.admin');
    }
}
