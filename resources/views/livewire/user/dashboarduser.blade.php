<div>
    <x-notification-container />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">

        <x-product-header :products="$products" :status="$status" :colors="$colors" :sizes="$sizes" />

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 products-grid">
            @if ($products->count() > 0)
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            @else
                <x-prod-static />
            @endif
        </div>
    </main>
</div>

@livewireScripts
