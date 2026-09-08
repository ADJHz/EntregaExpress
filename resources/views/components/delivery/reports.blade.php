<section id="reportes" x-show="activeSection === 'reportes'" x-transition
    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7" aria-labelledby="reports-heading">
    <div>
        <h2 id="reports-heading" class="text-xl font-bold text-slate-950">Reportes</h2>
        <p class="mt-1 text-sm text-slate-500">Descarga un archivo CSV compatible con Excel.</p>
    </div>
    <form method="GET" action="{{ route('reportes.entregas') }}" class="mt-5 flex flex-col gap-3 sm:flex-row"
        @submit="downloadReport"><select name="estado"
            class="rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-[#8a1c27] focus:ring-4 focus:ring-[#e9b8bd]">
            <option value="ambos">Recibidos y pendientes</option>
            <option value="recibidos">Solo recibidos</option>
            <option value="pendientes">Solo pendientes</option>
        </select><button type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#68151d] px-5 py-3 text-sm font-semibold text-[#f7df82] hover:bg-[#8a1c27]"><span
                x-show="loadingReport"
                class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>Generar reporte
            Excel</button></form>
</section>
