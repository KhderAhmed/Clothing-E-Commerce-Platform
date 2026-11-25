<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ClothingAssistantService
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('clothing-assistant');
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  🛡️ الرد الرئيسي — نقطة الدخول
    |═══════════════════════════════════════════════════════════
    */

    public function reply(string $message, array $context = [], ?int $userId = null, ?string $sessionId = null): array
    {
        $startTime = microtime(true);

        $normalized = $this->normalize($message);
        $normalized = $this->normalizeDialects($normalized);
        $expanded   = $this->expandWithSynonyms($normalized);

        // داخل flow؟
        if (!empty($context['active_flow'])) {
            $result = $this->continueFlow($message, $expanded, $context);
            $this->logAnalytic($userId, $sessionId, $message, $result['reply'], $result['intent'], true, $startTime, $result['entities'] ?? []);
            return $result;
        }

        // flow جديد؟
        $newFlow = $this->detectFlowStart($expanded, $message);
        if ($newFlow) {
            $result = $this->startFlow($newFlow, $context);
            $this->logAnalytic($userId, $sessionId, $message, $result['reply'], 'flow_start', true, $startTime);
            return $result;
        }

        // التحليل العادي — نمرّر النص الأصلي أيضاً
        $intent   = $this->detectIntent($expanded, $message);
        $entities = $this->extractEntities($expanded, $message);

        // 🛡️ الطبقة 2: بحث مباشر إذا ما فهم
        if ($intent === 'unknown' && mb_strlen($message) > 2) {
            $directSearch = $this->searchProducts([
                'keywords'  => $this->extractKeywords($expanded),
                'colors'    => $entities['colors'],
                'category'  => $entities['category'],
                'max_price' => $entities['max_price'],
                'occasion'  => null,
                'gender'    => null,
                'sizes'     => [],
            ]);

            if (!empty($directSearch)) {
                $result = [
                    'intent'      => 'direct_search',
                    'entities'    => $entities,
                    'reply'       => 'وجدت لك هذه المنتجات 🎯',
                    'products'    => $directSearch,
                    'suggestions' => ['شوف بدائل', 'أقل من 100'],
                    'context'     => $this->updateContext($context, 'direct_search', $entities),
                ];
                $this->logAnalytic($userId, $sessionId, $message, $result['reply'], 'direct_search', true, $startTime, $entities);
                return $result;
            }
        }

        // 🛡️ الطبقة 3: بناء الرد + الشخصية
        $reply = $this->buildReply($intent, $entities, $message, $context);
        $reply = $this->attachPersonality($reply, $intent);

        $products = $this->shouldSearchProducts($intent, $entities)
            ? $this->searchProducts($entities)
            : [];

        // ✨ إذا كان يبحث وما لقى، قلها صريح
        if ($this->shouldSearchProducts($intent, $entities) && empty($products)) {
            $reply .= "\n\n😔 ما لقيت منتجات مطابقة حالياً.\nجرب كلمات ثانية أو خفف الفلاتر.";
        } elseif (!empty($products)) {
            $prompts = config('clothing-assistant.action_prompts', ['شو رأيك؟']);
            $reply .= "\n\n" . $prompts[array_rand($prompts)];
        }

        $result = [
            'intent'      => $intent,
            'entities'    => $entities,
            'reply'       => $reply,
            'products'    => $products,
            'suggestions' => $this->suggestionsFor($intent),
            'context'     => $this->updateContext($context, $intent, $entities),
            'understood'  => $intent !== 'unknown',
        ];

        $this->logAnalytic($userId, $sessionId, $message, $reply, $intent, $intent !== 'unknown', $startTime, $entities);

        return $result;
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  ✏️ التطبيع + Fuzzy
    |═══════════════════════════════════════════════════════════
    */

    protected function normalize(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[\x{064B}-\x{065F}\x{0670}]/u', '', $text);
        $text = preg_replace('/[أإآٱ]/u', 'ا', $text);
        $text = preg_replace('/[ىئ]/u', 'ي', $text);
        $text = preg_replace('/ة/u', 'ه', $text);
        $text = preg_replace('/\s+/u', ' ', $text);
        return trim($text);
    }

    protected function fuzzyContains(string $haystack, string $needle, ?int $threshold = null): bool
    {
        $threshold ??= $this->config['settings']['fuzzy_distance'] ?? 2;

        if (str_contains($haystack, $needle)) {
            return true;
        }

        if (mb_strlen($needle) <= 3) {
            $threshold = 1;
        }

        $hayWords = preg_split('/\s+/u', $haystack);
        $needleLen = mb_strlen($needle);

        foreach ($hayWords as $word) {
            $wordLen = mb_strlen($word);

            if (abs($wordLen - $needleLen) > $threshold) {
                continue;
            }

            if (levenshtein($word, $needle) <= $threshold) {
                return true;
            }
        }

        return false;
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  📖 توسيع بالمرادفات
    |═══════════════════════════════════════════════════════════
    */

    protected function expandWithSynonyms(string $message): string
    {
        foreach ($this->config['synonyms'] as $main => $syns) {
            foreach ($syns as $syn) {
                if ($this->fuzzyContains($message, $this->normalize($syn))) {
                    $message .= ' ' . $main;
                    break;
                }
            }
        }

        return $message;
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  🗣️ كشف اللهجات — بدون كلمات الـ triggers
    |═══════════════════════════════════════════════════════════
    */

    protected function normalizeDialects(string $msg): string
    {
        // لا تحوّل: شني، شو، وش، بدي، ابغى، ابي، عايز — لأنها triggers في intents
        $dialects = [
            'شحال'   => 'كم',
            'وين'    => 'اين',
            'هكي'    => 'هكذا',
            'باهي'   => 'جيد',
            'توا'    => 'الان',
            'منيش'   => 'لست',
            'برشا'   => 'كثير',
            'شويه'   => 'قليل',
            'ازيك'   => 'كيف حالك',
            'كده'    => 'هكذا',
            'دلوقتي' => 'الان',
            'لسه'    => 'لم',
            'معلش'   => 'لا باس',
            'زين'    => 'جيد',
            'شلون'   => 'كيف',
            'هيك'    => 'هكذا',
            'هلق'    => 'الان',
            'كتير'   => 'كثير',
            'منيح'   => 'جيد',
            'وريني'  => 'اعرض',
            'فرجني'  => 'اعرض',
        ];

        foreach ($dialects as $dialect => $standard) {
            $msg = preg_replace('/\b' . preg_quote($dialect, '/') . '\b/u', $standard, $msg);
        }

        return $msg;
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  تحديد النية — يفحص النص الأصلي + المعالج
    |═══════════════════════════════════════════════════════════
    */

    protected function detectIntent(string $message, ?string $original = null): string
    {
        $textsToCheck = array_filter([$message, $original]);

        $scores = [];

        foreach ($this->config['intents'] as $intent => $data) {
            $score = 0;
            foreach ($data['keywords'] as $kw) {
                $normKw = $this->normalize($kw);
                foreach ($textsToCheck as $text) {
                    if ($this->fuzzyContains($text, $normKw)) {
                        $score += mb_strlen($normKw);
                        break;
                    }
                }
            }
            $scores[$intent] = $score;
        }

        $max = max($scores);
        return $max === 0 ? 'unknown' : array_search($max, $scores);
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  استخراج الكيانات
    |═══════════════════════════════════════════════════════════
    */

    protected function extractEntities(string $normalized, string $original): array
    {
        return [
            'colors'    => $this->extractColors($normalized),
            'sizes'     => $this->extractSizes($normalized),
            'max_price' => $this->extractMaxPrice($normalized),
            'occasion'  => $this->extractOccasion($normalized),
            'gender'    => $this->extractGender($normalized),
            'category'  => $this->extractCategory($normalized),
            'keywords'  => $this->extractKeywords($normalized),
        ];
    }

    protected function extractColors(string $msg): array
    {
        $found = [];
        foreach ($this->config['colors'] as $ar => $en) {
            if ($this->fuzzyContains($msg, $this->normalize($ar))) {
                $found[] = $en;
            }
        }
        return array_unique($found);
    }

    protected function extractSizes(string $msg): array
    {
        $sizes = [];

        if (preg_match_all('/\b(xs|s|m|l|xl|xxl|xxxl)\b/i', $msg, $m)) {
            $sizes = array_map('strtoupper', $m[1]);
        }
        if (preg_match_all('/\b(3[6-9]|4[0-9]|5[0-2])\b/', $msg, $m)) {
            $sizes = array_merge($sizes, $m[0]);
        }

        return array_unique($sizes);
    }

    protected function extractMaxPrice(string $msg): ?float
    {
        if (preg_match('/(?:اقل من|تحت|بحدود|حدود|لغايه|الي|حد|حوالي)\s*(\d+)/u', $msg, $m)) {
            return (float) $m[1];
        }
        if (preg_match('/(\d+)\s*(دينار|د\.ل|ريال|درهم|جنيه|دولار)/u', $msg, $m)) {
            return (float) $m[1];
        }
        return null;
    }

    protected function extractOccasion(string $msg): ?string
    {
        foreach ($this->config['occasions'] as $key => $data) {
            foreach ($data['keywords'] as $kw) {
                if ($this->fuzzyContains($msg, $this->normalize($kw))) {
                    return $key;
                }
            }
        }
        return null;
    }

    protected function extractGender(string $msg): ?string
    {
        if (preg_match('/(رجال|رجل|ذكر|اولاد|شباب|ولدي)/u', $msg))  return 'men';
        if (preg_match('/(نساء|انثى|بنات|سيدات|بنتي)/u', $msg))     return 'women';
        if (preg_match('/(اطفال|طفل|طفله)/u', $msg))                 return 'kids';
        return null;
    }

    protected function extractCategory(string $msg): ?string
    {
        foreach ($this->config['synonyms'] as $main => $syns) {
            $allForms = array_merge([$main], $syns);
            foreach ($allForms as $form) {
                if ($this->fuzzyContains($msg, $this->normalize($form))) {
                    return $main;
                }
            }
        }
        return null;
    }

    protected function extractKeywords(string $msg): array
    {
        $stop = [
            'بدي',
            'ابغى',
            'اريد',
            'ابحث',
            'عن',
            'شي',
            'احلى',
            'جميل',
            'ممكن',
            'لي',
            'عندكم',
            'هل',
            'في',
            'مع',
            'على',
            'من',
            'الى',
            'هذا',
            'هذه',
            'ذا',
            'دي',
            'the',
            'a',
            'an',
            'is',
            'شني',
            'شو',
            'وش',
            'ماذا',
            'ايش',
            'هات',
            'وريني',
        ];

        $words = preg_split('/\s+/u', $msg);
        return array_values(array_filter(
            $words,
            fn($w) => mb_strlen($w) > 2 && !in_array($w, $stop)
        ));
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  بناء الرد — مع تمرير السياق
    |═══════════════════════════════════════════════════════════
    */

    protected function buildReply(string $intent, array $entities, string $original, array $context = []): string
    {
        $faq = $this->matchFaq($original);
        if ($faq) return $faq;

        return match ($intent) {
            'greeting'   => $this->randomResponse('greeting'),
            'thanks'     => $this->randomResponse('thanks'),
            'goodbye'    => $this->randomResponse('goodbye'),
            'affirm'     => $this->randomResponse('affirm'),
            'size_help'  => $this->sizeHelpReply(),
            'color_help' => $this->colorHelpReply($entities),
            'budget'     => $this->budgetReply($entities),
            'occasion'   => $this->occasionReply($entities),
            'recommend'  => 'تمام! هذه أفضل الاقتراحات لك:',
            default      => $this->fallbackReply($entities, $context),
        };
    }

    protected function matchFaq(string $original): ?string
    {
        $normalized = $this->normalize($original);
        foreach ($this->config['faq'] as $keyword => $answer) {
            if ($this->fuzzyContains($normalized, $this->normalize($keyword))) {
                return $answer;
            }
        }
        return null;
    }

    protected function randomResponse(string $intent): string
    {
        $responses = $this->config['intents'][$intent]['responses'] ?? ['كيف أساعدك؟'];
        return $responses[array_rand($responses)];
    }

    protected function sizeHelpReply(): string
    {
        return "لاختيار المقاس المناسب:\n" .
            "• طول 160-170 سم → S أو M\n" .
            "• طول 170-180 سم → M أو L\n" .
            "• طول 180-190 سم → L أو XL\n" .
            "• طول فوق 190 سم → XL أو XXL\n\n" .
            "تبي أعطيك مقاسك بالضبط؟ اكتب: «شو مقاسي؟» 🎯";
    }

    protected function colorHelpReply(array $entities): string
    {
        if (!empty($entities['colors'])) {
            $pairs = [
                'black' => 'الأسود يناسب: الأبيض، الرمادي، الأحمر، الذهبي.',
                'white' => 'الأبيض يناسب: أي لون، خاصة الأزرق والبيج والأسود.',
                'red'   => 'الأحمر يناسب: الأسود، الأبيض، والكحلي.',
                'blue'  => 'الأزرق يناسب: الأبيض، البيج، والرمادي.',
                'green' => 'الأخضر يناسب: البيج، البني، والأبيض.',
                'beige' => 'البيج يناسب: البني، الأبيض، والكحلي.',
            ];
            return $pairs[$entities['colors'][0]] ?? 'اختيار رائع! هذا اللون يناسب معظم الإطلالات.';
        }

        return "اختيار الألوان يعتمد على:\n" .
            "• لون البشرة: الألوان الدافئة للفاتحة، الباردة للغامقة.\n" .
            "• المناسبة: هادئة (كحلي، رمادي) للعمل، زاهية (أحمر، ذهبي) للحفلات.\n" .
            "• الموسم: فاتحة للصيف، غامقة للشتاء.";
    }

    protected function budgetReply(array $entities): string
    {
        if ($entities['max_price']) {
            return "تمام، سأبحث لك عن منتجات بأقل من {$entities['max_price']} د.ل:";
        }
        return "ما هي ميزانيتك التقريبية؟ 💰";
    }

    protected function occasionReply(array $entities): string
    {
        if ($entities['occasion'] && isset($this->config['occasions'][$entities['occasion']])) {
            return $this->config['occasions'][$entities['occasion']]['response'];
        }
        return 'اختر ملابس تناسب المناسبة، وهذه اقتراحات:';
    }

    /**
     * ✨ Fallback ذكي يستخدم السياق
     */
    protected function fallbackReply(array $entities, array $context = []): string
    {
        // 1. إذا كتب لون فقط
        if (!empty($entities['colors'])) {
            $color = $entities['colors'][0];
            return "تمام، تبحث عن شي **{$color}** 🎨\nأي نوع تحديداً؟ قميص، بنطال، حذاء...؟";
        }

        // 2. إذا كتب نوع ملابس فقط
        if (!empty($entities['category'])) {
            return "تمام! تبي **{$entities['category']}** 🎯\nهذا اللي عندنا:";
        }

        // 3. إذا في سياق سابق عن مقاس
        if (($context['last_intent'] ?? null) === 'size_help') {
            return "من السياق، كنت تسأل عن المقاس 🎯\nاكتب \"شو مقاسي؟\" لنجيب عليه بالتفصيل.";
        }

        // 4. Fallback عام
        $messages = config('clothing-assistant.fallback_messages', []);
        if (!empty($messages)) {
            return $messages[array_rand($messages)];
        }

        return "لم أفهم طلبك تماماً 🤔\nجرب مثلاً:\n• \"بدي قميص أزرق\"\n• \"شو مقاسي؟\"";
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  🔄 Flows
    |═══════════════════════════════════════════════════════════
    */

    protected function detectFlowStart(string $expanded, ?string $original = null): ?string
    {
        $textsToCheck = array_filter([$expanded, $original]);

        foreach ($this->config['flows'] as $flowName => $flow) {
            foreach ($flow['trigger_keywords'] as $kw) {
                foreach ($textsToCheck as $text) {
                    if ($this->fuzzyContains($text, $this->normalize($kw))) {
                        return $flowName;
                    }
                }
            }
        }
        return null;
    }

    protected function startFlow(string $flowName, array $context): array
    {
        $flow = $this->config['flows'][$flowName];
        $firstStep = $flow['steps'][0];

        $newContext = $context;
        $newContext['active_flow'] = $flowName;
        $newContext['flow_step']   = 0;
        $newContext['flow_data']   = [];

        return [
            'intent'      => 'flow_start',
            'entities'    => [],
            'reply'       => "تمام ✨ {$firstStep['question']}",
            'products'    => [],
            'suggestions' => ['تخطي', 'إلغاء'],
            'context'     => $newContext,
        ];
    }

    protected function continueFlow(string $original, string $expanded, array $context): array
    {
        $flowName = $context['active_flow'];
        $step     = $context['flow_step'];
        $data     = $context['flow_data'] ?? [];
        $flow     = $this->config['flows'][$flowName];

        if ($this->fuzzyContains($expanded, 'الغاء') || $this->fuzzyContains($expanded, 'كنسل')) {
            return [
                'intent'      => 'flow_cancelled',
                'entities'    => [],
                'reply'       => 'تم إلغاء العملية 👍 كيف أقدر أساعدك؟',
                'products'    => [],
                'suggestions' => $this->config['suggestions'],
                'context'     => $this->clearFlow($context),
            ];
        }

        $currentField = $flow['steps'][$step]['field'];

        if ($this->fuzzyContains($expanded, 'تخطي')) {
            $data[$currentField] = null;
        } else {
            $data[$currentField] = $this->extractFlowValue($currentField, $expanded, $original);
        }

        $nextStep = $step + 1;
        if ($nextStep >= count($flow['steps'])) {
            return $this->finishFlow($flowName, $data, $context);
        }

        $newContext = $context;
        $newContext['flow_step'] = $nextStep;
        $newContext['flow_data'] = $data;

        return [
            'intent'      => 'flow_continue',
            'entities'    => [],
            'reply'       => $flow['steps'][$nextStep]['question'],
            'products'    => [],
            'suggestions' => ['تخطي', 'إلغاء'],
            'context'     => $newContext,
        ];
    }

    protected function extractFlowValue(string $field, string $expanded, string $original): mixed
    {
        return match ($field) {
            'height', 'weight', 'budget' => $this->extractNumber($original),
            'gender'                      => $this->extractGender($expanded) ?? $original,
            'occasion'                    => $this->extractOccasion($expanded) ?? $original,
            default                       => $original,
        };
    }

    protected function extractNumber(string $text): ?float
    {
        if (preg_match('/(\d+(?:\.\d+)?)/', $text, $m)) {
            return (float) $m[1];
        }
        return null;
    }

    protected function finishFlow(string $flowName, array $data, array $context): array
    {
        $clearContext = $this->clearFlow($context);

        if ($flowName === 'size_recommendation') {
            $reply = $this->calculateSize($data);
        } elseif ($flowName === 'outfit_recommendation') {
            $reply = $this->buildOutfitRecommendation($data);
        } else {
            $reply = 'تم ✅';
        }

        $entities = [
            'occasion'  => $data['occasion'] ?? null,
            'max_price' => $data['budget'] ?? null,
            'colors'    => [],
            'gender'    => $data['gender'] ?? null,
            'keywords'  => [],
            'sizes'     => [],
            'category'  => null,
        ];

        $products = $this->searchProducts($entities);

        return [
            'intent'      => 'flow_finished',
            'entities'    => $entities,
            'reply'       => $reply,
            'products'    => $products,
            'suggestions' => $this->config['suggestions'],
            'context'     => $clearContext,
        ];
    }

    protected function calculateSize(array $data): string
    {
        $h = (float) ($data['height'] ?? 0);
        $w = (float) ($data['weight'] ?? 0);
        $g = $data['gender'] ?? 'any';

        if ($h < 100 || $h > 250) {
            return 'الطول غير منطقي 🤔 اكتب الطول بالسنتيمتر (مثال: 175).';
        }

        if ($h < 160)     $size = 'S';
        elseif ($h < 170) $size = 'M';
        elseif ($h < 180) $size = 'L';
        elseif ($h < 190) $size = 'XL';
        else              $size = 'XXL';

        $adjustment = '';
        if ($w > 0) {
            $bmi = $w / (($h / 100) ** 2);

            if ($bmi > 28) {
                $adjustment = ' (يُفضّل مقاس أكبر لراحة أكثر)';
            } elseif ($bmi < 18) {
                $adjustment = ' (مقاس أصغر قد يكون أنسب)';
            }
        }

        $genderLabel = match ($g) {
            'men'   => 'رجالي',
            'women' => 'نسائي',
            default => '',
        };

        return "بناءً على طولك ({$h} سم)" .
            ($w > 0 ? " ووزنك ({$w} كغ)" : "") . ":\n\n" .
            "🎯 **المقاس المثالي: {$size}** {$genderLabel}{$adjustment}\n\n" .
            "💡 نصيحة: تحقق من جدول القياسات الخاص بكل منتج لأن المقاسات قد تختلف قليلاً.";
    }

    protected function buildOutfitRecommendation(array $data): string
    {
        $occasion = $data['occasion'] ?? null;
        $budget   = $data['budget'] ?? null;

        $base = "إليك إطلالة مقترحة:\n\n";

        if ($occasion && isset($this->config['occasions'][$occasion])) {
            $base .= "👔 " . $this->config['occasions'][$occasion]['response'] . "\n\n";
        } else {
            $base .= "👔 اختر قطعة رئيسية (قميص/فستان) بألوان هادئة، وأكملها بإكسسوار.\n\n";
        }

        if ($budget) {
            $base .= "💰 ضمن ميزانية {$budget} د.ل — رح أبحث لك عن أفضل الخيارات:";
        }

        return $base;
    }

    protected function clearFlow(array $context): array
    {
        unset($context['active_flow'], $context['flow_step'], $context['flow_data']);
        return $context;
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  🧠 تتبع السياق
    |═══════════════════════════════════════════════════════════
    */

    protected function updateContext(array $context, string $intent, array $entities): array
    {
        $context['last_intent'] = $intent;

        if (!empty($entities['colors'])) {
            $context['last_color'] = $entities['colors'][0];
        }
        if (!empty($entities['occasion'])) {
            $context['last_occasion'] = $entities['occasion'];
        }
        if (!empty($entities['category'])) {
            $context['last_category'] = $entities['category'];
        }
        if (!empty($entities['max_price'])) {
            $context['last_budget'] = $entities['max_price'];
        }

        $context['last_message_at'] = now()->timestamp;

        return $context;
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  البحث في المنتجات
    |═══════════════════════════════════════════════════════════
    */

    protected function shouldSearchProducts(string $intent, array $entities): bool
    {
        if (in_array($intent, ['recommend', 'budget', 'occasion'])) return true;
        if (!empty($entities['colors']))    return true;
        if (!empty($entities['keywords']))  return true;
        if (!empty($entities['max_price'])) return true;
        if (!empty($entities['category']))  return true;
        return false;
    }

    protected function searchProducts(array $entities): array
    {
        if (!class_exists(Product::class)) return [];

        $query = Product::query();

        if (Schema::hasColumn('products', 'status')) {
            $query->where('status', 'published');
        }

        if (!empty($entities['max_price'])) {
            $query->where('price', '<=', $entities['max_price']);
        }

        if (!empty($entities['gender']) && Schema::hasColumn('products', 'gender')) {
            $query->where('gender', $entities['gender']);
        }

        if (!empty($entities['colors']) && Schema::hasTable('product_variants')) {
            $query->where(function ($q) use ($entities) {
                foreach ($entities['colors'] as $color) {
                    $q->orWhereHas(
                        'variants.color',
                        fn($qq) => $qq->where('name', 'like', "%{$color}%")
                    );
                }
            });
        }

        $searchTerms = [];
        if (!empty($entities['category'])) $searchTerms[] = $entities['category'];
        if (!empty($entities['keywords'])) $searchTerms = array_merge($searchTerms, $entities['keywords']);

        if (!empty($searchTerms)) {
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $word) {
                    $q->orWhere('name', 'like', "%{$word}%")
                        ->orWhere('description', 'like', "%{$word}%");
                }
            });
        }

        return $query->latest()
            ->limit($this->config['settings']['max_products'])
            ->get(['id', 'name', 'price', 'image', 'description'])
            ->toArray();
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  اقتراحات
    |═══════════════════════════════════════════════════════════
    */

    protected function suggestionsFor(string $intent): array
    {
        return match ($intent) {
            'greeting'  => ['بدي إطلالة للعمل', 'اقترح لي ألوان', 'شو مقاسي؟'],
            'size_help' => ['شو مقاسي؟', 'طولي 175'],
            'occasion'  => ['أبي إطلالة كاجوال', 'بدي شي للشتاء'],
            'budget'    => ['أقل من 100 د.ل', 'شي اقتصادي'],
            default     => $this->config['suggestions'],
        };
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  ✨ الشخصية
    |═══════════════════════════════════════════════════════════
    */

    protected function attachPersonality(string $reply, string $intent): string
    {
        $p = config('clothing-assistant.personality', []);

        if ($intent === 'greeting' && !empty($p['greeting'])) {
            return $p['greeting'][array_rand($p['greeting'])];
        }

        if (in_array($intent, ['recommend', 'budget', 'occasion']) && rand(0, 2) === 0 && !empty($p['praise'])) {
            $praise = $p['praise'][array_rand($p['praise'])];
            return $praise . "\n\n" . $reply;
        }

        return $reply;
    }

    /*
    |═══════════════════════════════════════════════════════════
    |  📊 حفظ التحليلات
    |═══════════════════════════════════════════════════════════
    */

    protected function logAnalytic(
        ?int $userId,
        ?string $sessionId,
        string $userMessage,
        string $botReply,
        string $intent,
        bool $understood,
        float $startTime,
        array $entities = []
    ): void {
        try {
            \App\Models\ChatAnalytic::create([
                'user_id'          => $userId,
                'session_id'       => $sessionId,
                'intent'           => $intent,
                'user_message'     => mb_substr($userMessage, 0, 1000),
                'bot_reply'        => mb_substr($botReply, 0, 2000),
                'understood'       => $understood,
                'response_time_ms' => (int) ((microtime(true) - $startTime) * 1000),
                'entities'         => $entities,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to log analytic', ['error' => $e->getMessage()]);
        }
    }
}
