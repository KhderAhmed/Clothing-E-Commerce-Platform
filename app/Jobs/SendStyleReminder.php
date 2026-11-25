<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendStyleReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public array $products = []
    ) {}

    public function handle(): void
    {
        $user = User::find($this->userId);
        if (!$user || !$user->email) return;

        $body = "أهلاً {$user->name} 🌟\n\n";
        $body .= "لاحظنا أنك كنت تبحث عن إطلالة جديدة!\n\n";

        if (!empty($this->products)) {
            $body .= "هذه الاقتراحات اللي كنت تشوفها:\n";
            foreach ($this->products as $p) {
                $body .= "• {$p['name']} — {$p['price']} د.ل\n";
            }
            $body .= "\n";
        }

        $body .= "تعال شوف الجديد! عندنا عروض حلوة تناسبك.\n\n";
        $body .= "من هنا: " . url('/') . "\n\n";
        $body .= "مساعد الأزياء 💫";

        // استخدم Notification أو Mail مباشرة
        // Mail::raw($body, fn($m) => $m->to($user->email)->subject('اقتراحات أزياء تنتظرك 🌟'));
    }
}