<main class="main">

    <!-- Page Title -->
    <div class="page-title position-relative" data-aos="fade"
        style="background-image: url(assets/img/page-title-bg.webp);">

    </div><!-- End Page Title -->
    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

        <!-- Section Title -->
        <div class="container section-title py-2" data-aos="fade-up">
            <h2>Reserva Sucesso</h2>
            <p>Reserva Feita Com Sucesso<br></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">
            <p><strong><?= $nome ?></strong> A sua reserva de uma mesa para <strong><?= $numero ?></strong> pessoas no <?= APP_NAME ?> foi feita com sucesso</p>
            <p><strong>Foi enviado um email para a sua conta com os dados da reversa, caso não apareca na mensagens verifica a caixa de span!</p>
            <div class="col-12">
                <h4>Método de Pagamento</h4>
                <p>IBAN: <Strong><?= IBAN ?></Strong></p>
                <p>Numero de conta: <Strong><?= CONTA ?></Strong></p>
                <p>Transferência Express: <Strong><?= EXPRESS ?></Strong></p>
                <p>Acesso a Painel do usuario para submeter o comprovativo. <small class="text-primary m-0">O comprovativo tem que ser submetido dentro de um pediodo de 2 horas</small></p>
            </div>

            <p><strong>A sua reserva será processada!</p>
            <a href="?a=inicio" class="btn btn-primary">Inicio</a>
            <a href="?a=painel_usuario" class="btn btn-primary">Painel Usuario</a>
        </div>

    </section><!-- /Starter Section Section -->

</main>