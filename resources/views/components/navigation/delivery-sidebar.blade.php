<div id="delivery-sidebar-overlay" hidden class="fixed inset-0 z-30 bg-slate-950/50 lg:hidden" aria-hidden="true"></div>

<aside id="delivery-sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col bg-[#68151d] text-white transition-transform lg:translate-x-0"
    aria-label="Navegación principal">
    <div class="flex h-24 items-center gap-3 border-b border-[#d4af37]/35 bg-[#4d1016] px-6">
        <img src="{{ asset('Escudo.png') }}" alt="Escudos oficiales" class="h-50 w-80 object-contain object-left">
    </div>
    <div class="px-6 py-5">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#f2d675]">EntregaExpress</p>
        <p class="mt-1 text-sm text-[#f4e8c1]/75">Gestión de uniformes</p>
    </div>
    <nav class="space-y-1 px-4 text-sm font-medium">
        <button type="button" @click="showSection('buscar')"
            :class="activeSection === 'buscar' ?
                'bg-[#d4af37] text-[#4d1016] shadow-md shadow-black/20 ring-1 ring-[#f7df82]' :
                'text-[#f4e8c1]/85 hover:bg-[#d4af37]/10 hover:text-white'"
            class="flex w-full items-center gap-3 rounded-lg px-3 py-3 text-left transition">
            <span class="text-[#f2d675]" aria-hidden="true">⌕</span> Buscar elemento
        </button>
        <button type="button" @click="showSection('recibidos')"
            :class="activeSection === 'recibidos' ? 'bg-[#d4af37]/18 text-[#f7df82] ring-1 ring-[#d4af37]/35' :
                'text-[#f4e8c1]/85 hover:bg-[#d4af37]/10 hover:text-white'"
            class="flex w-full items-center gap-3 rounded-lg px-3 py-3 text-left transition">
            <span class="text-[#f2d675]" aria-hidden="true">✓</span> Reimprimir acuse
        </button>
        <button type="button" @click="showSection('reportes')"
            :class="activeSection === 'reportes' ? 'bg-[#d4af37]/18 text-[#f7df82] ring-1 ring-[#d4af37]/35' :
                'text-[#f4e8c1]/85 hover:bg-[#d4af37]/10 hover:text-white'"
            class="flex w-full items-center gap-3 rounded-lg px-3 py-3 text-left transition">
            <span class="text-[#f2d675]" aria-hidden="true">▤</span> Reportes
        </button>
    </nav>
</aside>
