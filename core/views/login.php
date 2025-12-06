<main class="main">

    <!-- Page Title -->
    <div class="page-title position-relative" data-aos="fade" style="background-image: url(assets/img/page-title-bg.webp);">
        <div class="container position-relative">
            <p>Entrar na sua conta de utilizador para visualizar o histórico de pedidos e preferências</p>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="?a=inicio">Home</a></li>
                    <li class="current">Login</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">
        <!-- Section Title -->
        <div class="container section-title py-2" data-aos="fade-up">
            <h2>Login</h2>
            <p>Iniciar Sessão<br></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">
            <p>Não tenho uma conta de utilizador <a href="?a=criar_conta">Criar conta</a></p>
        </div>

        <div class="container">
            <div class="col-sm-8">
                <form action="?a=login_submit" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                    <div class="row gy-3">
                        <div class="">
                            <input type="email" name="email" placeholder="Usuário" class="form-control" required>
                        </div>
                        <div class="">
                            <input type="password" name="senha" placeholder="Senha" class="form-control" required>
                        </div>

                        <div class="col-md-12 text-center">
                            <!-- Alerta para exibir a mensagem -->
                            <?php if (isset($_SESSION['mensagem'])) : ?>
                                <div class="alert alert-danger text-center p-2 mt-3">
                                    <?= $_SESSION['mensagem'] ?>
                                    <?php unset($_SESSION['mensagem']) ?>
                                </div>
                            <?php endif; ?>

                            <button type="submit">Entrar</button>

                        </div>
                    </div>

                </form>
            </div>
        </div>

    </section><!-- /Contact Section -->
</main>