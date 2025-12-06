<?php
$pagina_ativa = $_GET['a'] ?? 'painel_usuario';
?>

<!-- NAVBAR MOBILE -->
<nav class="navbar navbar-dark bg-dark d-md-none">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuMobile">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="navbar-brand mb-0 h1">Painel do Usuário</span>
    </div>
</nav>

<!-- OFFCANVAS MENU MOBILE -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="menuMobile">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Painel do Usuário</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-between h-100">
        <div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="?a=painel_usuario" class="nav-link text-white <?= $pagina_ativa === 'painel_usuario' ? 'active' : '' ?>">
                        <i class="bi bi-house-door me-2"></i> Início
                    </a>
                </li>
                <li>
                    <a href="?a=historico_reservas" class="nav-link text-white <?= $pagina_ativa === 'historico_reservas' ? 'active' : '' ?>">
                        <i class="bi bi-calendar-check me-2"></i> Reservas
                    </a>
                </li>
                <li>
                    <a href="?a=historico_pedidos" class="nav-link text-white <?= $pagina_ativa === 'historico_pedidos' ? 'active' : '' ?>">
                        <i class="bi bi-bag-check me-2"></i> Pedidos
                    </a>
                </li>
            </ul>
        </div>
        <!-- Dropdown do usuário no final -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <img src="assets/img/imagem-user.png" alt="Foto" width="32" height="32" class="rounded-circle me-2">
                <strong><?= $_SESSION['usuario'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="?a=editar_perfil">Editar Perfil</a></li>
                <li><a class="dropdown-item" href="?a=alterar_senha">Alterar Senha</a></li>
                <li><a class="dropdown-item" href="?a=perfil">Perfil</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="?a=inicio">Restaurante</a></li>
                <li><a class="dropdown-item" href="?a=logout">Sair</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- CONTEÚDO EM TELAS MAIORES -->
<main class="d-flex flex-nowrap">
    <!-- SIDEBAR ESQUERDA FIXA EM MD+ -->
    <div class="d-none d-md-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px; min-height: 100vh;">
        <a href="?a=painel_usuario" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4">Painel do Usuário</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="?a=painel_usuario" class="nav-link text-white <?= $pagina_ativa === 'painel_usuario' ? 'active' : '' ?>">
                    <i class="bi bi-house-door me-2"></i> Início
                </a>
            </li>
            <li>
                <a href="?a=historico_reservas" class="nav-link text-white <?= $pagina_ativa === 'historico_reservas' ? 'active' : '' ?>">
                    <i class="bi bi-calendar-check me-2"></i> Reservas
                </a>
            </li>
            <li>
                <a href="?a=historico_pedidos" class="nav-link text-white <?= $pagina_ativa === 'historico_pedidos' ? 'active' : '' ?>">
                    <i class="bi bi-bag-check me-2"></i> Pedidos
                </a>
            </li>
        </ul>


        <!-- DROPDOWN DO USUÁRIO NO FINAL -->
        <div class="dropdown mt-auto">
            <hr class="mt-auto">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <img src="assets/img/imagem-user.png" alt="Foto" width="32" height="32" class="rounded-circle me-2">
                <strong><?= $_SESSION['usuario'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="?a=editar_perfil">Editar Perfil</a></li>
                <li><a class="dropdown-item" href="?a=alterar_senha">Alterar Senha</a></li>
                <li><a class="dropdown-item" href="?a=perfil">Perfil</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="?a=inicio">Restaurante</a></li>
                <li><a class="dropdown-item" href="?a=logout">Sair</a></li>
            </ul>
        </div>
    </div>

    <!-- BARRA DIVISÓRIA (só aparece em MD+) -->
    <div class="b-example-divider b-example-vr d-none d-md-block"></div>

    <!-- CONTEÚDO PRINCIPAL -->
    <div class="flex-grow-1">