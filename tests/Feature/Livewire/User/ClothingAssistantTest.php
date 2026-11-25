<?php

namespace Tests\Feature\Livewire\User;

use App\Livewire\User\ClothingAssistant;
use App\Models\ChatOutcome;
use App\Models\User;
use App\Models\UserPreference;
use App\Services\ClothingAssistantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClothingAssistantTest extends TestCase
{
    use RefreshDatabase;
public function test_greets_authenticated_user_with_saved_preferences()
    {
        $user = User::factory()->create(['name' => 'خضر']);
        UserPreference::create([
            'user_id' => $user->id,
            'preferred_size' => 'L',
            'favorite_colors' => ['أزرق', 'أسود'],
        ]);

        Livewire::actingAs($user)
            ->test(ClothingAssistant::class)
            ->set('isOpen', true) // 👈 فتح نافذة الدردشة لعرض رسالة الترحيب
            ->assertSee('أهلاً بعودتك خضر')
            ->assertSee('L');
    }
    public function test_sends_message_receives_reply_from_service_and_updates_user_memory()
    {
        $user = User::factory()->create();

        $this->mock(ClothingAssistantService::class, function ($mock) {
            $mock->shouldReceive('reply')
                ->once()
                ->andReturn([
                    'reply' => 'إليك أفضل الخيارات باللون الأسود',
                    'products' => [],
                    'intent' => 'search_product',
                    'entities' => [
                        'colors' => ['أسود'],
                        'max_price' => 300,
                    ],
                    'suggestions' => ['عرض المزيد'],
                    'context' => ['flow_data' => ['height' => 175]],
                ]);
        });

        Livewire::actingAs($user)
            ->test(ClothingAssistant::class)
            ->set('userMessage', 'أريد قميص أسود تحت 300')
            ->call('sendMessage')
            ->assertHasNoErrors()
            ->assertSet('userMessage', '')
            ->assertDispatched('message-added');

        $prefs = UserPreference::where('user_id', $user->id)->first();
        
        $this->assertNotNull($prefs);
        $this->assertEquals('L', $prefs->preferred_size);
        $this->assertEquals(300.0, (float) $prefs->typical_budget);
    }

    public function test_can_rate_the_assistant_experience_and_record_outcome()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ClothingAssistant::class)
            ->call('rateExperience', 5)
            ->assertSet('rating', 5)
            ->assertSet('showRating', false);

        $this->assertTrue(
            ChatOutcome::where('user_id', $user->id)->where('rating', 5)->exists()
        );
    }
}