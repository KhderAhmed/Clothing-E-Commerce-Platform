<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AdminOrders extends Component
{
    use WithPagination;

    public $statusFilter = 'all';

    public $selectedOrder = null;


    /**
     * =========================================================
     * تحديث الطلبات عند وصول طلب جديد عبر Reverb / Pusher
     * =========================================================
     */
    #[On('refreshOrders')]
    public function refreshOrders()
    {
        $this->resetPage();

        // إعادة تحميل الطلب المحدد إن وجد
        if ($this->selectedOrder) {
            $this->selectedOrder = Order::with('items.product')
                ->find($this->selectedOrder->id);
        }
    }

    /**
     * =========================================================
     * تغيير فلتر الحالة
     * =========================================================
     */
    public function updatedStatusFilter()
    {
        $this->resetPage();

        // إزالة الطلب المحدد عند تغيير الفلتر
        $this->selectedOrder = null;
    }


    /**
     * =========================================================
     * عرض تفاصيل الطلب
     * =========================================================
     */
    public function showOrder($orderId)
    {
        $this->selectedOrder = Order::with('items.product')
            ->findOrFail($orderId);
    }


    /**
     * =========================================================
     * تحديث حالة الطلب
     * =========================================================
     */
    public function updateStatus($orderId, $newStatus)
    {
        /*
        |--------------------------------------------------------------------------
        | التأكد من أن المستخدم Admin
        |--------------------------------------------------------------------------
        */

        abort_unless(
            auth()->check() && auth()->user()->role === 'admin',
            403
        );


        /*
        |--------------------------------------------------------------------------
        | التحقق من أن الحالة موجودة ضمن OrderStatus
        |--------------------------------------------------------------------------
        */

        $status = OrderStatus::tryFrom($newStatus);

        if (!$status) {

            $this->dispatch(
                'showNotification',
                message: '❌ حالة الطلب غير صحيحة.',
                type: 'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | جلب الطلب
        |--------------------------------------------------------------------------
        */

        $order = Order::findOrFail($orderId);


        /*
        |--------------------------------------------------------------------------
        | الحصول على الحالة الحالية كـ string
        |--------------------------------------------------------------------------
        */

        $currentStatus = $order->status instanceof OrderStatus
            ? $order->status
            : OrderStatus::tryFrom((string) $order->status);


        /*
        |--------------------------------------------------------------------------
        | لا يوجد تغيير
        |--------------------------------------------------------------------------
        */

        if ($currentStatus === $status) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | لا نسمح بتعديل الطلب بعد التسليم أو الإلغاء
        |--------------------------------------------------------------------------
        */

        if (
            $currentStatus === OrderStatus::DELIVERED ||
            $currentStatus === OrderStatus::CANCELLED
        ) {

            $this->dispatch(
                'showNotification',
                message: '⚠️ لا يمكن تعديل حالة طلب تم تسليمه أو إلغاؤه.',
                type: 'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | حفظ الحالة الجديدة
        |--------------------------------------------------------------------------
        */

        $order->update([
            'status' => $status->value,
        ]);


        /*
        |--------------------------------------------------------------------------
        | تحديث الطلب المحدد في الكرت الجانبي
        |--------------------------------------------------------------------------
        */

        if (
            $this->selectedOrder &&
            $this->selectedOrder->id == $orderId
        ) {

            $this->selectedOrder = Order::with('items.product')
                ->find($orderId);
        }


        /*
        |--------------------------------------------------------------------------
        | إعادة تحميل البيانات
        |--------------------------------------------------------------------------
        */

        $this->resetPage();


        /*
        |--------------------------------------------------------------------------
        | Notification
        |--------------------------------------------------------------------------
        */

        $this->dispatch(
            'showNotification',
            message: "🔄 تم تحديث حالة الطلب رقم #{$order->id} إلى: {$status->label()}",
            type: 'success'
        );
    }


    /**
     * =========================================================
     * Render
     * =========================================================
     */
    public function render()
    {
        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = Order::with([
            'items.product',
        ])->latest();


        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        if ($this->statusFilter !== 'all') {

            $query->where(
                'status',
                $this->statusFilter
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $orders = $query->paginate(10);


        /*
        |--------------------------------------------------------------------------
        | إحصائيات الحالات
        |--------------------------------------------------------------------------
        */

        $statusCounts = [

            'pending' => Order::where(
                'status',
                OrderStatus::PENDING->value
            )->count(),

            'confirmed' => Order::where(
                'status',
                OrderStatus::CONFIRMED->value
            )->count(),

            'processing' => Order::where(
                'status',
                OrderStatus::PROCESSING->value
            )->count(),

            'shipped' => Order::where(
                'status',
                OrderStatus::SHIPPED->value
            )->count(),

            'delivered' => Order::where(
                'status',
                OrderStatus::DELIVERED->value
            )->count(),

            'cancelled' => Order::where(
                'status',
                OrderStatus::CANCELLED->value
            )->count(),

        ];


        return view(
            'livewire.admin.admin-orders',
            [
                'orders' => $orders,
                'statusCounts' => $statusCounts,
            ]
        )->layout('layouts.admin');
    }
}
