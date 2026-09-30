<header class="sticky top-0 z-50 border-b border-slate-200 bg-[#4d1016] backdrop-blur">
    <div class="flex min-h-20 items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button id="delivery-sidebar-toggle" type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border-2 border-[#d4af37] bg-[#68151d] text-[#f7df82] shadow-md shadow-black/20 transition hover:bg-[#8a1c27] hover:text-white focus:outline-none focus:ring-4 focus:ring-[#d4af37]/40"
                aria-label="Mostrar menú" aria-expanded="false">☰</button>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#f7df82]">Secretaría de Seguridad del
                    Estado de México</p>
                <h1 class="text-xl font-bold text-[#fff8df] sm:text-2xl">Sistema de uniformes</h1>
            </div>
        </div>
        <div x-show="!isOnline || pendingReceipts.length" x-cloak
            class="max-w-xs rounded-lg border border-[#d4af37]/60 bg-[#68151d] px-3 py-2 text-right text-xs font-semibold text-[#fff8df]">
            <span x-show="!isOnline">Sin conexión. El avance se conserva en este equipo.</span>
            <span x-show="isOnline && pendingReceipts.length">Conexión recuperada. Sincronizando recepción pendiente...</span>
        </div>
        <img src="{{ asset('Escudos.png') }}" alt="Escudos oficiales" class="hidden h-11 w-36 object-contain sm:block">
    </div>
</header>
