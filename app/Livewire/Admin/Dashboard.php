<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseComponent;
use App\Models\Product;

class Dashboard extends BaseComponent
{
        public $title = 'Admin Dashboard';
   
    public function getChartDataProperty(): array
    {
        // آخر 7 أيام
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));

        // المنتجات المضافَة يومياً
        $products = Product::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->pluck('total', 'day');

        // الطلبات يومياً
        $orders = \App\Models\Order::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->pluck('total', 'day');

        return [
            'labels'   => $days->map(fn($d) => \Carbon\Carbon::parse($d)->translatedFormat('D d/m'))->toArray(),
            'products' => $days->map(fn($d) => (int) ($products[$d] ?? 0))->toArray(),
            'orders'   => $days->map(fn($d) => (int) ($orders[$d] ?? 0))->toArray(),
        ];
    }
    public function refreshChart()
    {
        $this->dispatch('chart-refresh');
    }



    protected function view(): string
    {
        return 'livewire.admin.dashboard';
    }
}
