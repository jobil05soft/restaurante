<?php
// calcula o numero de produtos no carrinho

$total_item = 0;
if (isset($_SESSION['carrinho'])) {
  foreach ($_SESSION['carrinho'] as $quantidade) {
    $total_item += $quantidade;
  }
}
?>

<header id="header" class="header fixed-top">

  <div class="branding d-flex align-items-cente">

    <div class="container position-relative d-flex align-items-center justify-content-between">
      <a href="?a=inicio" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename">Restaurante</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="?a=inicio" class="active">Home<br></a></li>
          <li><a href="#about">Sobre</a></li>
          <li><a href="?a=cardapio">Cardápio</a></li>
          <li><a href="#specials">Especiais</a></li>
          <li><a href="#events">Eventos</a></li>
          <li><a href="#gallery">Galeria</a></li>

          <li><a href="#contact">Contato</a></li>
          <li class="d-xl-none"><a href="?a=login">login</a></li>


        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="me-0 btn-book-a-table d-none d-xl-block" href="?a=reservar_mesa">Reserva Mesa</a>

      <?php if (!core\classes\Store::logado()): ?>
        <a class="mx-0 btn-book-a-table d-none d-xl-block" href="?a=login">Login | <i class="bi bi-box-arrow-in-right"></i></a>
      <?php else: ?>
        <a class="mx-0 btn-book-a-table d-none d-xl-block" href="?a=painel_usuario">Perfil</a>
      <?php endif; ?>

      <div class="">
        <!-- <a href="?a=carrinho"><i class="ms-4 fas fa-shopping-cart"></i></a> -->
        <a href="?a=carrinho" class=""><i class="fas fa-shopping-cart"></i></a>
        <span class="badge bg-warning mx-0" id="carrinho"><?= $total_item == 0 ? '' : $total_item ?></span>
        <!--  -->
      </div>

    </div>

  </div>

</header>