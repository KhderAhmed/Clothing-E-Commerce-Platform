<?php

namespace App\Livewire\User;

use App\Models\ChatOutcome;
use App\Models\Product;
use App\Models\UserPreference;
use App\Services\CartService;
use App\Services\ClothingAssistantService;
use Livewire\Component;

class ClothingAssistant extends Component
{
    public array  $messages      = [];
    public string $userMessage   = '';
    public bool   $isLoading     = false;
    public bool   $isOpen        = false;
    public array  $suggestions   = [];
    public array  $context       = [];

    public ?int    $rating       = null;
    public bool    $showRating   = false;
    public bool    $hasPurchased = false;
    public ?string $sessionId    = null;

    /*
    |═══════════════════════════════════════════════════════════
    |  🚀 Mount
    |═══════════════════════════════════════════════════════════
    */

    public function mount(): void
    {
        $this->messages    = session('chat_messages', []);
        $this->context     = session('chat_context', []);
        $this->suggestions = config('clothing-assistant.suggestions');
        $this->sessionId   = session()->getId();

        if (empty($this->messages)) {
            $this->messages[] = [
                'role'    => 'assistant',
                'content' => $this->buildSmartGreeting(),
            ];
        }
    }

    protected function buildSmartGreeting(): string
    {
        if (!auth()->check()) {
            return "أهلاً بك 👋\nأنا مساعدك الشخصي لاختيار الملابس.\n\nيمكنني:\n• اقتراح إطلالات\n• تحديد مقاسك\n• البحث حسب اللون والميزانية";
        }

        $prefs = UserPreference::where('user_id', auth()->id())->first();

        if (!$prefs || !$prefs->preferred_size) {
            return "أهلاً بك 👋\nأنا مساعدك الشخصي لاختيار الملابس.\nكيف أقدر أساعدك اليوم؟";
        }

        $name   = auth()->user()->name;
        $size   = $prefs->preferred_size;
        $colors = $prefs->favorite_colors ?? [];

        $greeting  = "أهلاً بعودتك {$name} 🌟\n\n";
        $greeting .= "من آخر زيارة، عرفت أنك تفضل مقاس **{$size}**";

        if (!empty($colors)) {
            $greeting .= " وألوان " . implode('، ', array_slice($colors, 0, 3));
        }

        $greeting .= ".\n\nكيف أساعدك اليوم؟";

        return $greeting;
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  💬 إرسال الرسائل
    |═══════════════════════════════════════════════════════════
    */

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage(ClothingAssistantService $service): void
    {
        $this->validate([
            'userMessage' => 'required|string|max:' . config('clothing-assistant.settings.max_message_len'),
        ]);

        $text = trim($this->userMessage);
        $this->userMessage = '';

        $this->messages[] = ['role' => 'user', 'content' => $text];
        $this->dispatch('message-added');

        $this->isLoading = true;

        try {
            $result = $service->reply($text, $this->context, auth()->id(), $this->sessionId);
        } catch (\Throwable $e) {
            \Log::error('Chat error', ['error' => $e->getMessage()]);
            $result = [
                'reply'       => 'عذراً، حدث خطأ تقني. جرب تسأل بطريقة ثانية 💪',
                'products'    => [],
                'suggestions' => $this->suggestions,
                'context'     => $this->context,
            ];
        }

        $this->messages[] = [
            'role'     => 'assistant',
            'content'  => $result['reply'],
            'products' => $result['products'] ?? [],
            'intent'   => $result['intent'] ?? 'unknown',
        ];

        $this->suggestions = $result['suggestions'] ?? config('clothing-assistant.suggestions');
        $this->context     = $result['context'] ?? [];

        $this->updateUserMemory($result['entities'] ?? []);

        if (
            count(array_filter($this->messages, fn($m) => $m['role'] === 'assistant')) === 4
            && $this->rating === null
        ) {
            $this->showRating = true;
        }

        $maxHistory = config('clothing-assistant.settings.max_history');
        if (count($this->messages) > $maxHistory) {
            $this->messages = array_slice($this->messages, -$maxHistory);
        }

        session([
            'chat_messages' => $this->messages,
            'chat_context'  => $this->context,
        ]);

        $this->isLoading = false;
        $this->dispatch('message-added');
    }

    protected function updateUserMemory(array $entities): void
    {
        if (!auth()->check()) return;

        $prefs = UserPreference::firstOrCreate(['user_id' => auth()->id()]);

        foreach ($entities['colors'] ?? [] as $color) {
            $prefs->addFavoriteColor($color);
        }

        if (!empty($entities['occasion'])) {
            $prefs->preferred_style = $entities['occasion'];
        }

        if (!empty($entities['max_price'])) {
            $prefs->typical_budget = $entities['max_price'];
        }

        if (!empty($this->context['flow_data']['height'])) {
            $size = $this->calculateSizeLabel((float) $this->context['flow_data']['height']);
            if ($size) $prefs->preferred_size = $size;
        }

        $prefs->interactions_count = ($prefs->interactions_count ?? 0) + 1;
        $prefs->last_seen_at = now();
        $prefs->save();
    }

    protected function calculateSizeLabel(float $height): ?string
    {
        if ($height < 100 || $height > 250) return null;
        if ($height < 160) return 'S';
        if ($height < 170) return 'M';
        if ($height < 180) return 'L';
        if ($height < 190) return 'XL';
        return 'XXL';
    }

    public function useSuggestion(string $text): void
    {
        $this->userMessage = $text;
    }

    public function resetChat(): void
    {
        session()->forget(['chat_messages', 'chat_context']);

        $this->messages = [[
            'role'    => 'assistant',
            'content' => $this->buildSmartGreeting(),
        ]];

        $this->suggestions = config('clothing-assistant.suggestions');
        $this->context     = [];
        $this->showRating  = false;
        $this->rating      = null;
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  🛒 السلة — عبر CartService الموحّد
    |═══════════════════════════════════════════════════════════
    */

    public function addToCart(int $productId, CartService $cart): void
    {
        if (!$cart->add($productId)) {
            return;
        }

        $product = Product::find($productId);

        $this->messages[] = [
            'role'    => 'assistant',
            'content' => "🎉 تمت إضافة **{$product->name}** للسلة!\n\nتبي شي ثاني؟ أو تروح للسلة؟",
        ];

        ChatOutcome::create([
            'user_id'       => auth()->id(),
            'session_id'    => $this->sessionId,
            'found_product' => true,
            'added_to_cart' => true,
        ]);

        $complements = $this->findComplementary($product);
        if (!empty($complements)) {
            $this->messages[] = [
                'role'     => 'assistant',
                'content'  => '💡 ممكن يعجبك كمان:',
                'products' => $complements,
            ];
        }

        $this->dispatch('message-added');
        $this->dispatch('cart-updated');
    }

    public function addAllToCart(array $productIds, CartService $cart): void
    {
        $count = 0;
        foreach ($productIds as $id) {
            if ($cart->add($id)) $count++;
        }

        $this->messages[] = [
            'role'    => 'assistant',
            'content' => "🎉 تمت إضافة {$count} منتجات للسلة!",
        ];

        ChatOutcome::create([
            'user_id'       => auth()->id(),
            'session_id'    => $this->sessionId,
            'found_product' => true,
            'added_to_cart' => true,
        ]);

        $this->dispatch('message-added');
        $this->dispatch('cart-updated');
    }

    protected function findComplementary(Product $product): array
    {
        $matching = [
            'shirt' => ['بنطال', 'حذاء', 'حزام'],
            'قميص'  => ['بنطال', 'حذاء', 'حزام'],
            'فستان' => ['حذاء', 'حقيبه', 'اكسسوار'],
            'بنطال' => ['قميص', 'حذاء', 'حزام'],
            'حذاء'  => ['حزام', 'حقيبه'],
        ];

        $keywords = $matching[$product->category ?? ''] ?? ['حذاء', 'حزام', 'حقيبه'];

        return Product::where('status', 'published')
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('name', 'like', "%{$kw}%")
                      ->orWhere('category', 'like', "%{$kw}%");
                }
            })
            ->inRandomOrder()
            ->limit(3)
            ->get(['id', 'name', 'price', 'image'])
            ->toArray();
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  ⭐ CSAT Rating
    |═══════════════════════════════════════════════════════════
    */

    public function rateExperience(int $rating): void
    {
        $this->rating     = $rating;
        $this->showRating = false;

        ChatOutcome::create([
            'user_id'    => auth()->id(),
            'session_id' => $this->sessionId,
            'rating'     => $rating,
        ]);

        $this->messages[] = [
            'role'    => 'assistant',
            'content' => $rating >= 4
                ? "شكراً على تقييمك 🌟 سعيد إن التجربة عجبتك!\nفي أمان الله 👋"
                : "شكراً على صراحتك 🙏\nنأسف إن التجربة ما كانت بالمستوى المطلوب.\nممكن تقول لنا شو اللي ما عجبك؟ نتحسن لك 💪",
        ];

        $this->dispatch('message-added');
    }

    public function skipRating(): void
    {
        $this->showRating = false;
    }

    public function goodbyeNudge(): void
    {
        if (auth()->check()) {
            session(['pending_reminder' => true]);
        }
    }

    public function markPurchaseCompleted(int $orderId): void
    {
        $this->hasPurchased = true;

        ChatOutcome::create([
            'user_id'            => auth()->id(),
            'session_id'         => $this->sessionId,
            'found_product'      => true,
            'added_to_cart'      => true,
            'completed_purchase' => true,
        ]);

        $this->messages[] = [
            'role'    => 'assistant',
            'content' => "مبروك على الشراء! 🎉\n\nلو احتجت شي ينسق مع القطعة، أنا هنا 💫",
        ];

        $this->dispatch('message-added');
    }

    public function render()
    {
        return view('livewire.user.clothing-assistant');
    }
}