<div id="notificationContainer"
    class="fixed top-20 left-4 right-4 z-50 flex flex-col gap-2 max-w-xs mx-auto pointer-events-none">
</div>

<script>
    function showNotification(message, type = 'info') {
        const container = document.getElementById('notificationContainer');
        if (!container) return;

        const colors = {
            success: 'bg-emerald-600 dark:bg-emerald-500',
            error: 'bg-red-600 dark:bg-red-500',
            info: 'bg-[#f53003]',
            warning: 'bg-amber-600 dark:bg-amber-500'
        };

        const icons = { success: '✓', error: '✗', info: 'ℹ', warning: '⚠' };

        const notification = document.createElement('div');
        notification.className = `${colors[type] || colors.info} text-white px-4 py-3 rounded-xl shadow-lg mb-2 pointer-events-auto border border-white/10 flex items-center gap-3 transform transition-all duration-300 translate-x-full`;
        notification.innerHTML = `
            <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">${icons[type] || icons.info}</div>
            <span class="text-xs font-medium flex-1">${message}</span>
            <button onclick="this.parentElement.remove()" class="text-white/70 hover:text-white transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        `;

        container.appendChild(notification);
        setTimeout(() => { notification.classList.remove('translate-x-full'); notification.classList.add('translate-x-0'); }, 10);
        setTimeout(() => { notification.classList.remove('translate-x-0'); notification.classList.add('translate-x-full'); setTimeout(() => notification.remove(), 300); }, 4000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        Livewire.on('showNotification', (event) => {
            const data = event.detail ? event.detail : event[0] || event;
            showNotification(data.message, data.type);
        });

        Livewire.on('wishlistCountUpdated', (event) => {
            const data = event.detail ? event.detail : event[0] || event;
            const wishlistCountElement = document.querySelector('.wishlist-count');
            if (wishlistCountElement) {
                wishlistCountElement.textContent = data.count;
                wishlistCountElement.classList.toggle('hidden', data.count <= 0);
            }
        });

        Livewire.on('cartUpdated', (event) => {
            const data = event.detail ? event.detail : event[0] || event;
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.totalCount;
                cartCountElement.classList.toggle('hidden', data.totalCount <= 0);
            }
        });
    });
</script>