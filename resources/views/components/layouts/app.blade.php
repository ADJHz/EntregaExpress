@props([
    'title' => config('app.name', 'EntregaExpress'),
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f766e">
    <title>{{ $title }} | {{ config('app.name', 'EntregaExpress') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    {{ $slot }}
    @stack('scripts')
</body>

</html>
