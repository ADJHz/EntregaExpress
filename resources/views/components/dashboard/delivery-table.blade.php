@props(['deliveries' => []])

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-950">Entregas recientes</h2>
            <p class="mt-1 text-sm text-slate-500">Consulta el avance y el estado de cada elemento.</p>
        </div>
        <button type="button"
            class="inline-flex items-center justify-center rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">Cargar
            CSV</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[47.5rem] text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th scope="col" class="px-5 py-3">Elemento</th>
                    <th scope="col" class="px-5 py-3">Ubicación</th>
                    <th scope="col" class="px-5 py-3">Coordinación</th>
                    <th scope="col" class="px-5 py-3">Uniforme</th>
                    <th scope="col" class="px-5 py-3">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($deliveries as $delivery)
                    <tr class="hover:bg-slate-50">
                        <td class="whitespace-nowrap px-5 py-4"><span
                                class="font-semibold text-slate-950">{{ $delivery['nombre'] }}</span><span
                                class="block text-xs text-slate-500">{{ $delivery['csp'] }}</span></td>
                        <td class="whitespace-nowrap px-5 py-4">{{ $delivery['ubicacion'] }}</td>
                        <td class="whitespace-nowrap px-5 py-4">{{ $delivery['coordinacion'] }}</td>
                        <td class="whitespace-nowrap px-5 py-4">{{ $delivery['tipo_uniforme'] }}</td>
                        <td class="whitespace-nowrap px-5 py-4"><x-dashboard.status-badge :received="$delivery['recibio']" /></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <p class="font-semibold text-slate-950">Aún no hay entregas registradas</p>
                            <p class="mt-1 text-sm text-slate-500">Carga un archivo CSV para comenzar a gestionar el
                                inventario.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
