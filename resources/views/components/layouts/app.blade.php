<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Projeto IoT' }} · Monitoramento</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Projeto IoT - início">
            <span class="brand-mark">i</span>
            <span>projeto<span class="brand-light">iot</span><small>MONITORAMENTO</small></span>
        </a>
        <nav class="main-nav" aria-label="Navegação principal">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-active' : '' }}"><span>▦</span> Dashboard</a>
            <a href="{{ route('ambientes.index') }}" class="{{ request()->routeIs('ambientes.*') ? 'nav-active' : '' }}"><span>⌂</span> Ambientes</a>
            <a href="{{ route('sensores.index') }}" class="{{ request()->routeIs('sensores.*') ? 'nav-active' : '' }}"><span>◈</span> Sensores</a>
        </nav>
        <div class="topbar-right"><span class="system-tag"><i></i> Sistema IoT</span><span class="topbar-avatar">IoT</span></div>
    </header>
    <div class="app-shell">{{ $slot }}</div>
    <footer class="app-footer"><span>Projeto IoT</span><span>Monitoramento de ambientes e sensores</span></footer>
    @livewireScripts
    @stack('scripts')
</body>
</html>
