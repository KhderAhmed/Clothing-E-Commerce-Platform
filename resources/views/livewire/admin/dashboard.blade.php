<div>
    {{-- ══════════════════════════════════════════════════════════
     📊 الرسم البياني — الطلبات + المنتجات (آخر 7 أيام)
═══════════════════════════════════════════════════════════ --}}
<div class="bg-white dark:bg-[#0d0d10] border border-zinc-200/80 dark:border-zinc-800/80 rounded-2xl overflow-hidden mt-6">

    <div class="px-6 py-4 border-b border-zinc-200/80 dark:border-zinc-800/80 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2.5">
            <span class="w-1.5 h-1.5 rounded-full bg-[#f53003]"></span>
            <h2 class="text-sm font-black text-zinc-950 dark:text-white">
                الأداء خلال آخر 7 أيام
            </h2>
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-sm bg-[#f53003]"></span>
                <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-400">المنتجات</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-sm bg-emerald-700"></span>  {{-- ✅ أخضر غامق --}}
                <span class="text-[10px] font-bold text-zinc-600 dark:text-zinc-400">الطلبات</span>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="relative h-72" wire:ignore>
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    @script
    <script>
        let performanceChart = null;

        function renderPerformanceChart(data) {
            const canvas = document.getElementById('performanceChart');
            if (!canvas) return;

            if (performanceChart) {
                performanceChart.destroy();
            }

            const isDark = document.documentElement.classList.contains('dark');

            performanceChart = new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'المنتجات',
                            data: data.products,
                            backgroundColor: '#f53003',
                            hoverBackgroundColor: '#d32b02',
                            borderRadius: 6,
                            borderSkipped: false,
                            barThickness: 18,
                        },
                        {
                            label: 'الطلبات',
                            data: data.orders,
                            backgroundColor: '#047857',      // ✅ أخضر غامق
                            hoverBackgroundColor: '#065f46', // ✅ أغمق عند المرور
                            borderRadius: 6,
                            borderSkipped: false,
                            barThickness: 18,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 600,
                        easing: 'easeOutQuart',
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#18181b' : '#fafafa',
                            titleColor: isDark ? '#ffffff' : '#09090B',
                            bodyColor: isDark ? '#a1a1aa' : '#52525b',
                            borderColor: isDark ? '#27272a' : '#e4e4e7',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: true,
                            titleFont: {
                                family: 'Cairo',
                                weight: '700',
                                size: 12,
                            },
                            bodyFont: {
                                family: 'Cairo',
                                weight: '600',
                                size: 11,
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                color: isDark ? '#71717a' : '#a1a1aa',
                                font: {
                                    family: 'Cairo',
                                    weight: '700',
                                    size: 10,
                                },
                            },
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: isDark ? 'rgba(255,255,255,0.04)' : 'rgba(0,0,0,0.04)',
                                drawBorder: false,
                            },
                            ticks: {
                                stepSize: 1,
                                color: isDark ? '#71717a' : '#a1a1aa',
                                font: {
                                    family: 'Cairo',
                                    weight: '700',
                                    size: 10,
                                },
                            },
                        },
                    },
                },
            });
        }

        renderPerformanceChart(@json($this->chartData));

        Livewire.on('chart-refresh', () => {
            renderPerformanceChart(@json($this->chartData));
        });

        window.addEventListener('livewire:navigated', () => {
            renderPerformanceChart(@json($this->chartData));
        });
    </script>
    @endscript
</div>