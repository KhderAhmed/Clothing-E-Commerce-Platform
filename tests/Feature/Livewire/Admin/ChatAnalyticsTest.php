<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\ChatAnalytics;
use App\Models\ChatAnalytic;
use App\Models\ChatOutcome;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_analytics_component_calculates_metrics_correctly_for_selected_period()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        ChatAnalytic::factory()->create([
            'understood' => true,
            'response_time_ms' => 250,
            'created_at' => now()->subDays(2),
        ]);

        ChatOutcome::factory()->create([
            'rating' => 5,
            'added_to_cart' => true,
            'completed_purchase' => true,
            'created_at' => now()->subDays(2),
        ]);

        Livewire::actingAs($admin)
            ->test(ChatAnalytics::class)
            ->assertSet('period', '7')
            ->assertViewHas('totalMessages', 1)
            ->assertViewHas('understoodRate', 100.0)
            ->assertViewHas('avgResponseTime', 250)
            ->assertViewHas('csatScore', 100.0)
            ->assertViewHas('purchases', 1);
    }
}