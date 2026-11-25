<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseComponent;
use App\Models\ChatAnalytic;
use App\Models\ChatOutcome;

class ChatAnalytics extends BaseComponent
{
    public string $period = '7';

    protected function view(): string
    {
        return 'livewire.admin.chat-analytics';
    }

    public function with(): array
    {
        $since = now()->subDays((int) $this->period);

        // ملخص عام
        $totalMessages    = ChatAnalytic::where('created_at', '>=', $since)->count();
        $understoodCount  = ChatAnalytic::where('created_at', '>=', $since)->where('understood', true)->count();
        $avgResponseTime  = ChatAnalytic::where('created_at', '>=', $since)->avg('response_time_ms');
        $avgRating        = ChatOutcome::where('created_at', '>=', $since)->whereNotNull('rating')->avg('rating');

        // توزيع النوايا
        $intentsDistribution = ChatAnalytic::selectRaw('intent, COUNT(*) as count')
            ->where('created_at', '>=', $since)
            ->groupBy('intent')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // أكثر الأسئلة غير المفهومة
        $unknownQuestions = ChatAnalytic::where('created_at', '>=', $since)
            ->where('understood', false)
            ->latest()
            ->limit(10)
            ->pluck('user_message');

        // معدل التحويل
        $sessionsCount   = ChatOutcome::where('created_at', '>=', $since)->distinct('session_id')->count('session_id');
        $cartAdds        = ChatOutcome::where('created_at', '>=', $since)->where('added_to_cart', true)->count();
        $purchases       = ChatOutcome::where('created_at', '>=', $since)->where('completed_purchase', true)->count();

        // CSAT
        $ratingsCount = ChatOutcome::where('created_at', '>=', $since)->whereNotNull('rating')->count();
        $positiveRatings = ChatOutcome::where('created_at', '>=', $since)->where('rating', '>=', 4)->count();
        $csatScore = $ratingsCount > 0 ? round(($positiveRatings / $ratingsCount) * 100, 1) : 0;

        return [
            'totalMessages'       => $totalMessages,
            'understoodRate'      => $totalMessages > 0 ? round(($understoodCount / $totalMessages) * 100, 1) : 0,
            'avgResponseTime'     => round($avgResponseTime ?? 0),
            'avgRating'           => round($avgRating ?? 0, 1),
            'csatScore'           => $csatScore,
            'intentsDistribution' => $intentsDistribution,
            'unknownQuestions'    => $unknownQuestions,
            'sessionsCount'       => $sessionsCount,
            'cartAdds'            => $cartAdds,
            'purchases'           => $purchases,
            'conversionRate'      => $sessionsCount > 0 ? round(($purchases / $sessionsCount) * 100, 1) : 0,
        ];
    }
}