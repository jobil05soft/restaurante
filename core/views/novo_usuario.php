<main class="main">

    <!-- Page Title -->
    <div class="page-title position-relative" data-aos="fade" style="background-image: url(assets/img/page-title-bg.webp);">
        <div class="container position-relative">
            <p>Crie uma conta de utilizador para registrar o histórico de pedidos e preferências</p>

        </div>
    </div><!-- End Page Title -->



    <!-- Contact Section -->
    <section id="contact" class="contact section">


        <div class="container">
            <div class="row gy-4">

                <div class="col-lg-4">
                    <!-- Section Title -->
                    <div class="container section-title py-2 mt-5" data-aos="fade-up">
                        <h2>Conta</h2>
                        <p>Cadastar-se<br></p>
                    </div><!-- End Section Title -->

                    <div class="container" data-aos="fade-up">
                        <p>Já tenho uma conta de utilizador <a href="?a=login">Login</a></p>
                    </div>


                </div>

                <div class="col-lg-8">
                    <form action="?a=criar_conta_submit" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                        <div class="row gy-2">

                            <div class="col-md-6">
                                <input type="text" name="nome" class="form-control" placeholder="Seu Nome" required="">
                            </div>

                            <div class="col-md-6 ">
                                <input type="email" class="form-control" name="email" placeholder="Seu Email" required="">
                            </div>

                            <div class="col-md-6 ">
                                <input type="password" class="form-control" name="senha_1" placeholder="Senha" required="">
                            </div>

                            <div class="col-md-6 ">
                                <input type="password" class="form-control" name="senha_2" placeholder="Repetir a senha" required="">
                            </div>

                            <div class="col-md-6 ">
                                <input type="number" maxlength="14" class="form-control" name="telefone" placeholder="Telefone" required="">
                            </div>

                            <div class="col-md-12">
                                <input type="text" class="form-control" name="morada" placeholder="Cidade, Bairro, Nº Casa" required="">
                            </div>

                            <div class="col-md-12 text-center">
                                <?php if (isset($_SESSION['mensagem'])) : ?>
                                    <div class="alert alert-danger p-2 mt-3">
                                        <?= $_SESSION['mensagem'] ?>
                                        <?php unset($_SESSION['mensagem']) ?>
                                    </div>
                                <?php endif; ?>
                                <button type="submit">Subtmeter</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>

    </section><!-- /Contact Section -->
</main>