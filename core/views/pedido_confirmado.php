<main class="main">

    <!-- Page Title -->
    <div class="page-title position-relative" data-aos="fade"
        style="background-image: url(assets/img/page-title-bg.webp);">

    </div><!-- End Page Title -->
    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

        <!-- Section Title -->
        <div class="container section-title py-2" data-aos="fade-up">
            <h2>Pedido</h2>
            <p>Pedido Confirmado<br></p>
        </div><!-- End Section Title -->


        <div class="container" data-aos="fade-up">
            <p>Muito obrigado pelo seu Pedido.</p>

            <?php if ($tipo_pedido == 'em casa'): ?>
                <div class="mt-5">
                    <h4>Método de Pagamento</h4>
                    <p>IBAN: <Strong><?= IBAN ?></Strong></p>
                    <p>Numero de conta: <Strong><?= CONTA ?></Strong></p>
                    <p>Transferência Express: <Strong><?= EXPRESS ?></Strong></p>
                    <p>Acesso a Painel do usuario para submeter o comprovativo. <br> <small class="text-primary m-0">O comprovativo tem que ser submetido dentro de um pediodo de 2 horas</small></p>
                    <p>Total do Pedido: <strong><?= number_format($total_pedido, 2, ',', '.') . '$' ?></strong></p>

                    <p>
                        O seu Pedido só será processada após confirmação do pagamento.
                    </p>
                </div>
            <?php else: ?>

                <div class="mt-5">
                    <h4>Método de Pagamento</h4>
                    <p>IBAN: <Strong><?= IBAN ?></Strong></p>
                    <p>Numero de conta: <Strong><?= CONTA ?></Strong></p>
                    <p>Transferência Express: <Strong><?= EXPRESS ?></Strong></p>
                    <p>Acesso a Painel do usuario para submeter o comprovativo. <br> <small class="text-primary m-0">O comprovativo tem que ser submetido dentro de um pediodo de 2 horas</small></p>
                    <p>Total do Pedido: <strong><?= number_format($total_pedido, 2, ',', '.') . 'Kz' ?></strong></p>

                    <div class="text-info">

                        <p class="mt-2">
                            Por favor aguarde pelo atendimento! Que será rápido!...
                        </p>
                        <p>Enquanto espera por favor prepare os dados de pagamento!</p>
                        <p>Obrigado!</p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="my-5 offset-6">
                <a href="?a=inicio" class="btn btn-primary">Voltar</a>
                <a href="?a=painel_usuario" class="btn btn-primary">Painel Usuario</a>
            </div>


        </div>

    </section><!-- /Starter Section Section -->

</main>