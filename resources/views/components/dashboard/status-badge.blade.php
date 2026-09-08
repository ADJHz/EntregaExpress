@props(['received' => false])

<span
    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $received ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $received ? 'bg-emerald-600' : 'bg-amber-600' }}" aria-hidden="true"></span>
    {{ $received ? 'Recibido' : 'Pendiente' }}
</span>
