<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Projeto IoT' }} · Monitoramento</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    @livewireStyles
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Projeto IoT - início">
            <span class="brand-mark">i</span>
            <span>Projeto<span class="brand-light">Iot</span><small>MONITORAMENTO</small></span>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
