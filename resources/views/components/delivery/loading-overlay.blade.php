<div x-show="loadingAction || loadingReport"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/40 backdrop-blur-sm" role="status">
    <div class="flex items-center gap-3 rounded-xl bg-white px-5 py-4 text-sm font-semibold text-slate-800 shadow-xl">
        <span class="h-5 w-5 animate-spin rounded-full border-2 border-slate-200 border-t-[#8a1c27]"></span>
        <span x-text="loadingReport ? 'Preparando reporte...' : 'Generando acuse...'">Cargando...</span>
    </div>
</div>
