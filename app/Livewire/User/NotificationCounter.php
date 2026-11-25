<?php

namespace App\Livewire\User;
use Livewire\Component;
use Livewire\Attributes\On;

class NotificationCounter extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        // هنا يمكنك جلب عدد الإشعارات غير المقروءة من قاعدة البيانات للمستخدم المسجل
        $this->unreadCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
    }

    // الاستماع لحدث إرسال إشعار جديد من أي مكان في الباك إند
    #[On('notificationReceived')]
    public function incrementCount($count = null)
    {
        if ($count !== null) {
            $this->unreadCount = $count;
        } else {
            $this->unreadCount++;
        }
    }

    // دالة لتصفير العداد عند نقر المستخدم وقراءة الإشعارات
    public function markAsRead()
    {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
        }
        $this->unreadCount = 0;
    }

    public function render()
    {
        return view('livewire.user.notification-counter');
    }
}