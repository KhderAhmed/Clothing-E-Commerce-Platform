<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutPage extends Component
{
    public $cart = [];
    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;

    // بيانات الشحن
    public $name = '';
    public $phone = '';
    public $city = '';
    public $address = '';
    public $payment_method = 'cod'; // الدفع عند الاستلام كخيار افتراضي

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'phone' => 'required|string|min:8|max:20',
        'city' => 'required|string',
        'address' => 'required|string|min:10',
        'payment_method' => 'required|in:cod,card',
    ];

    protected $messages = [
        'name.required' => 'الاسم الكامل مطلوب لإتمام الشحن',
        'name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
        'phone.required' => 'رقم الهاتف مطلوب للتواصل عند التوصيل',
        'city.required' => 'يرجى اختيار أو كتابة المدينة',
        'address.required' => 'العنوان التفصيلي مطلوب لضمان وصول الشحنة',
        'address.min' => 'يرجى كتابة عنوان أكثر تفصيلاً (10 أحرف على الأقل)',
    ];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
        
        // إذا دخل المستخدم صفحة الدفع والسلة فارغة، نعيده لصفحة المنتجات
        if (count($this->cart) === 0) {
            return redirect()->to('/cart');
        }

        // إذا كان مسجّل دخول، نملأ الاسم تلقائياً كـ UX ذكي
        if (Auth::check()) {
            $this->name = Auth::user()->name;
        }

        $this->calculateTotals();
    }

    private function calculateTotals()
    {
        $this->subtotal = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $this->tax = $this->subtotal * 0.05;
        $this->total = $this->subtotal + $this->tax;
    }

    // معالجة وإتمام الطلب بدقة متناهية وحماية المعاملات (Database Transactions)
    public function placeOrder()
    {
        $this->validate();

        try {
            // استخدام DB Transaction لضمان حفظ كل شيء أو عدم حفظ أي شيء في حال حدوث خطأ مفاجئ
            DB::transaction(function () {
                
                // 1. إنشاء الطلب الرئيسي
                $order = Order::create([
                    'user_id' => Auth::id(), // سيسجل null إذا كان زائرًا، أو ID المستخدم
                    'customer_name' => $this->name,
                    'customer_phone' => $this->phone,
                    'city' => $this->city,
                    'address' => $this->address,
                    'payment_method' => $this->payment_method,
                    'subtotal' => $this->subtotal,
                    'tax' => $this->tax,
                    'total' => $this->total,
                    'status' => 'pending',
                ]);

                // 2. تحويل منتجات السلة إلى خطوط تفصيلية داخل الطلب (Order Items)
                foreach ($this->cart as $productId => $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }
            });

            // 3. تفريغ السلة من الجلسة بعد نجاح العملية
            session()->forget('cart');

            // 4. تحديث العداد العلوي بالـ Navbar فوراً (Real-time)
            $this->dispatch('cartUpdated', totalCount: 0);

            // 5. توجيه المستخدم لصفحة النجاح مع رسالة
            session()->flash('order_success', 'تهانينا! تم تسجيل طلبك بنجاح وجاري تجهيزه.');
            return redirect()->to('/order-success');

        } catch (\Exception $e) {
            // إظهار الخطأ عبر الـ Toast المطور الخاص بك في حال الفشل
            $this->dispatch('showNotification', message: '❌ عذراً، حدث خطأ أثناء معالجة الطلب، حاول مجدداً.', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.user.checkout-page')->layout('layouts.user');
    }
}