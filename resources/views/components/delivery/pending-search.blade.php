<section id="buscar" x-show="activeSection === 'buscar'" x-transition
    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7" aria-labelledby="search-heading">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <h2 id="search-heading" class="text-xl font-bold text-slate-950">Buscar elemento para entregar</h2>
            <p class="mt-1 text-sm text-slate-500">Busca por nombre o clave SP.</p>
        </div>
        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-[#8a1c27]"
            x-text="loadingPending ? 'Cargando...' : `${pendingEmployees.length} resultados`"></span>
    </div>
    <div class="relative mt-6">
        <label for="employee-search" class="mb-2 block text-sm font-medium text-slate-700">Nombre o clave SP</label>
        <input id="employee-search" x-model="query" @input.debounce.300ms="searchPending()" @focus="dropdownOpen = true"
            type="search" autocomplete="off" placeholder="Escribe para buscar..."
            class="w-full rounded-xl border border-slate-300 py-3 px-4 text-sm outline-none transition focus:border-[#8a1c27] focus:ring-4 focus:ring-[#e9b8bd]">
        <div x-show="dropdownOpen && query.trim()" x-transition
            class="absolute inset-x-0 top-19 z-20 max-h-72 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-xl"
            role="listbox">
            <div x-show="loadingPending" class="space-y-2 p-4">
                <div class="h-4 animate-pulse rounded bg-slate-200"></div>
                <div class="h-4 animate-pulse rounded bg-slate-200"></div>
                <div class="h-4 animate-pulse rounded bg-slate-200"></div>
            </div>
            <template x-for="employee in filteredEmployees" :key="employee.id"><button type="button"
                    @click="choose(employee)"
                    class="flex w-full flex-col border-b border-slate-100 px-4 py-3 text-left hover:bg-red-50"><span
                        class="font-semibold text-slate-900" x-text="employee.nombre"></span><span
                        class="mt-1 text-xs text-slate-500"><span x-text="employee.csp || 'Sin CSP'"></span> · <span
                            x-text="employee.ubicacion || 'Sin ubicación'"></span></span></button></template>
            <p x-show="!loadingPending && filteredEmployees.length === 0"
                class="px-4 py-5 text-center text-sm text-slate-500">No hay empleados pendientes con esa búsqueda.</p>
        </div>
    </div>
    <div x-show="selected" class="mt-6 rounded-xl border border-[#e9b8bd] bg-[#fff1f2] p-5" x-cloak>
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-[#8a1c27]">Empleado seleccionado</p>
                <h3 class="mt-1 text-lg font-bold text-slate-950" x-text="selected?.nombre"></h3>
            </div><button type="button" @click="clearSelection"
                class="text-sm font-medium text-red-800">Cambiar</button>
        </div>
        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-4">
            <div>
                <dt class="text-[#7d555a]">CSP</dt>
                <dd class="font-semibold" x-text="selected?.csp || 'Sin registrar'"></dd>
            </div>
            <div>
                <dt class="text-[#7d555a]">Ubicación</dt>
                <dd class="font-semibold" x-text="selected?.ubicacion || 'Sin registrar'"></dd>
            </div>
            <div>
                <dt class="text-[#7d555a]">Coordinación</dt>
                <dd class="font-semibold" x-text="selected?.coordinacion || 'Sin registrar'"></dd>
            </div>
            <div>
                <dt class="text-[#7d555a]">Género</dt>
                <dd class="font-semibold" x-text="selected?.genero || 'Sin registrar'"></dd>
            </div>
        </dl>
        <form method="POST" class="mt-5" :action="selected ? selected.receive_url : '#'" @submit="submitDelivery">
            <button type="submit" :disabled="loadingAction"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-[#b8941f] bg-[#d4af37] px-4 py-3 text-sm font-semibold text-[#4d1016] shadow-sm transition hover:bg-[#e6c34f] focus:outline-none focus:ring-4 focus:ring-[#d4af37]/35 disabled:opacity-60"><span
                    x-show="loadingAction"
                    class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span><span
                    x-text="loadingAction ? 'Generando acuse...' : 'Generar PDF, marcar como entregado e imprimir'"></span></button>@csrf
        </form>
    </div>
</section>
