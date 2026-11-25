# 🤖 Smart Clothing Assistant

مساعد ذكي لاختيار الملابس — Laravel Livewire — بدون API.

## ✨ المميزات

- 🧠 **Fuzzy Matching** — يقبل الأخطاء الإملائية
- 📖 **Synonyms** — يفهم المرادفات (سروال = بنطال)
- 🔄 **Multi-step Flows** — محادثات متعددة الخطوات
- 💭 **Context Tracking** — يتذكر المحادثة السابقة
- 🎨 كشف الألوان والمناسبات
- 💰 فلترة بالسعر والميزانية
- 📦 اقتراح منتجات حقيقية من DB
- 🌙 Dark Mode
- 🔒 **بدون Internet أو API keys**

## 🛠️ التقنيات

- Laravel 12
- Livewire 4
- Tailwind CSS
- Pure PHP — No external libraries

## 🚀 التثبيت

```bash
git clone <repo>
cd product-realtime
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

app/Services/ClothingAssistantService.php   ← العقل
config/clothing-assistant.php                ← القاموس
app/Livewire/ClothingAssistant.php           ← الكومبوننت
resources/views/livewire/clothing-assistant.blade.php