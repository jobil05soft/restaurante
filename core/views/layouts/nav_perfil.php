<header id="header" class="header fixed-top">


  <div class="branding d-flex align-items-cente">

    <div class="container position-relative d-flex align-items-center justify-content-between">
      <a href="?a=inicio" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename"><?= APP_NAME ?></h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <!-- <li><a href="?a=inicio" class="">Alterar dados Pessoais<br></a></li> -->
          <li><a href="?a=painel_usuario" class="">Inicio<br></a></li>
          <li><a href="?a=inicio" class="">Histórico Pedidos<br></a></li>
          <li><a href="?a=inicio" class="">Histórico Reservas<br></a></li>
          <li><a href="?a=perfil"><?= $_SESSION['usuario'] ?></a></li>
          <li><a href="?a=logout"><i style="font-size:large;" class="bi-large bi-box-arrow-in-left "></i></a></li>
          <li><a href="#specials"><i class="bi bi-store"></i></a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>

  </div>

</header>

<main class="main">

  <!-- Page Title -->
  <div class="page-title position-relative pb-0" data-aos="fade" style="background-image: url(assets/img/page-title-bg.webp);">
    <div class="container position-relative">

    </div>
  </div><!-- End Page Title -->