@props(['label', 'value', 'hint', 'tone' => 'teal'])

@php
    $tones = [
        'teal' => 'bg-teal-50 text-teal-700 ring-teal-100',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        'slate' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];
@endphp

<article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">{{ $value }}</p>
        </div>
        <span
            class="inline-flex rounded-lg px-3 py-2 text-sm font-semibold ring-1 {{ $tones[$tone] ?? $tones['teal'] }}">{{ $hint }}</span>
    </div>
</article>
