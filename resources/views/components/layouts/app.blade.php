<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard IoT — Painel Principal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    
    <!-- FontAwesome para os ícones da navbar e sensores -->
    <link rel="stylesheet" href="https://cloudflare.com">
    
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-custom { background-color: #1e293b; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: #f8fafc !important; }
        .navbar-custom .nav-link:hover { color: #38bdf8 !important; }
        .card-metric { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .card-metric:hover { transform: translateY(-3px); }
        .icon-circle { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .blink { animation: blinker 1.5s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }
    </style>
    @livewireStyles
</head>
<body>

    <!-- BARRA DE NAVEGAÇÃO SUPERIOR (NAVBAR) -->
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container-fluid px-4">
            <!-- Logo / Nome do Projeto -->
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-circle-nodes text-info me-2 blink"></i>
                <span>PROJETO IOT</span>
            </a>
            
            <!-- Botão Hamburguer para Celulares -->
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <!-- Links da Navbar -->
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-chart-pie me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa-solid fa-microchip me-1"></i> Dispositivos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa-solid fa-database me-1"></i> Histórico de Dados
                        </a>
                    </li>
                </ul>
                
                <!-- Status e Perfil de Usuário na Direita -->
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill d-none d-sm-inline">
                        <i class="fas fa-circle me-1 small"></i> Broker Conectado
                    </span>
                    
                    <div class="dropdown">
                        <a class="text-white text-decoration-none dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-info text-dark rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-weight: 600;">
                                U
                            </div>
                            <span class="d-none d-md-inline text-light">Usuário Aluno</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><a class="dropdown-menu-item dropdown-item py-2" href="#"><i class="fa-solid fa-user-gear me-2 text-muted"></i>Perfil</a></li>
                            <li><a class="dropdown-menu-item dropdown-item py-2" href="#"><i class="fa-solid fa-sliders me-2 text-muted"></i>Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-menu-item dropdown-item py-2 text-danger" href="#"><i class="fa-solid fa-right-from-bracket me-2"></i>Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTEÚDO PRINCIPAL DO LIVEWIRE (SLOT) -->
    <div class="container-fluid px-4">
        {{ $slot }}
    </div>

    <!-- Scripts do Bootstrap + Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    @livewireScripts
</body>
</html>
