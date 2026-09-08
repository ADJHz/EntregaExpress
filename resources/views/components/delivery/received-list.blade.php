<section id="recibidos" x-show="activeSection === 'recibidos'" x-transition
    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7" aria-labelledby="received-heading">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
        <div>
            <h2 id="received-heading" class="text-xl font-bold text-slate-950">Reimprimir acuse</h2>
            <p class="mt-1 text-sm text-slate-500">Consulta los nombres que ya recibieron y vuelve a imprimir su formato.
            </p>
        </div><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800"
            x-text="loadingReceived ? 'Cargando...' : `${receivedEmployees.length} recibidos`"></span>
    </div>
    <input x-model="receivedQuery" @input.debounce.300ms="searchReceived()" type="search"
        placeholder="Buscar entre recibidos..."
        class="mt-5 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#8a1c27] focus:ring-4 focus:ring-[#e9b8bd]">
    <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        <div x-show="loadingReceived" class="space-y-3 sm:col-span-2 lg:col-span-3">
            <div class="h-16 animate-pulse rounded-xl bg-slate-100"></div>
            <div class="h-16 animate-pulse rounded-xl bg-slate-100"></div>
        </div><template x-for="employee in receivedEmployees" :key="employee.id">
            <article
                class="flex items-center justify-between gap-3 rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-slate-900" x-text="employee.nombre"></p>
                    <p class="mt-1 text-xs text-slate-500" x-text="employee.csp || 'Sin CSP'"></p>
                </div><a :href="employee.reprint_url"
                    class="shrink-0 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">Reimprimir</a>
            </article>
        </template>
        <p x-show="!loadingReceived && receivedEmployees.length === 0"
            class="py-8 text-center text-sm text-slate-500 sm:col-span-2 lg:col-span-3">No hay entregas realizadas para
            mostrar.</p>
    </div>
</section>
