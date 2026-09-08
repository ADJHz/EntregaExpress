<x-layouts.app title="Entrega de uniformes">
    <div x-data="deliveryApp({
        pending: @js(route('entregas.pendientes')),
        received: @js(route('entregas.recibidos')),
    })" x-init="init()" x-cloak @click.outside="dropdownOpen = false"
        class="min-h-screen bg-slate-100">
        <x-delivery.loading-overlay />
        <x-navigation.delivery-sidebar />

        <div id="delivery-content" class="min-h-screen transition-[padding] duration-300">
            <x-navigation.delivery-header />

            <main class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <x-delivery.error-alert />
                <x-delivery.pending-search />
                <x-delivery.received-list />
                <x-delivery.reports />
            </main>
        </div>
    </div>
</x-layouts.app>
