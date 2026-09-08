<aside id="application-sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-slate-950 text-slate-100 transition-transform lg:translate-x-0"
    aria-label="Navegación principal">
    <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
        <span
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-400 font-bold text-slate-950">EE</span>
        <div>
            <p class="font-semibold tracking-wide">EntregaExpress</p>
            <p class="text-xs text-slate-400">Operaciones de entrega</p>
        </div>
    </div>
    <nav class="flex-1 space-y-1 px-4 py-6 text-sm font-medium">
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 rounded-lg bg-teal-400/15 px-3 py-2.5 text-teal-300" aria-current="page">
            <span aria-hidden="true">▦</span>
            <span>Resumen</span>
        </a>
        <a href="#entregas"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-slate-300 transition hover:bg-white/10 hover:text-white">
            <span aria-hidden="true">✓</span>
            <span>Entregas</span>
        </a>
        <a href="#personas"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-slate-300 transition hover:bg-white/10 hover:text-white">
            <span aria-hidden="true">◎</span>
            <span>Personas</span>
        </a>
        <a href="#carga"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-slate-300 transition hover:bg-white/10 hover:text-white">
            <span aria-hidden="true">↑</span>
            <span>Carga de CSV</span>
        </a>
    </nav>
    <div class="border-t border-white/10 p-4 text-xs text-slate-400">
        Plataforma preparada para crecer por módulos.
    </div>
</aside>
