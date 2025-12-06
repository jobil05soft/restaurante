<main class="main">

    <!-- Page Title -->
    <div class="page-title position-relative" data-aos="fade" style="background-image: url(assets/img/Galeria-3.webp);">
        <div class="container position-relative">
            <p>A reserva de uma mesa evita você ir a um restaurante e não encontrar mesa disponível</p>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="?a=inicio">Home</a></li>
                    <li class="current">Reservar Mesa</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <!-- Book A Table Section -->
    <section id="book-a-table" class="book-a-table section">

        <!-- Section Title -->
        <div class="container section-title py-0" data-aos="fade-up">
            <h2>RESERVA</h2>
            <p>Reservar Mesa</p>
        </div><!-- End Section Title -->

        <div class="container py-0" data-aos="fade-up" data-aos-delay="100">




            <?php if (empty($dados_usuario)): ?>


                <form action="?a=reservar_mesa_submit" method="post" role="form" class="php-email-form">
                    <div class="row gy-4">
                        <div class="col-lg-4 col-md-6">
                            <input type="text" name="nome" class="form-control" placeholder="Seu Nome" required>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <input type="email" class="form-control" name="email" placeholder="Seu Email" required>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <input type="number" maxlength="9" pattern="\d*" class="form-control" name="telefone" placeholder="Seu Telefone" required>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <input type="number" class="form-control" name="pessoas" placeholder="Nº de Pessoa" required>
                        </div>


                        <div class="col-lg-4 col-md-6 morada_alternativa ">
                            <div class="m-0 p-0 php-email-form">
                                <select id="mealType" class="form-select" name="tipo_refeicao" required>
                                    <option value="">Tipo de Refeição</option>
                                    <option value="Café Manhã">Café Manhã</option>
                                    <option value="Almoço">Almoço</option>
                                    <option value="Jantar">Jantar</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">

                            <!-- Input do calendário -->
                            <input type="datetime-local" name="data_time" id="datePicker" class="form-control" required>
                        </div>


                    </div>

                    <div class="form-group mt-3">
                        <textarea class="form-control" name="mensagem" rows="5" placeholder="Message"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="aceitaTermos" required />
                        <label class="form-check-label" for="aceitaTermos">
                            Eu li e concordo com os
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalTermos">Termos e Condições</a>.
                        </label>
                    </div>
                    <!-- Alerta para exibir a mensagem -->
                    <?php if (isset($_SESSION['mensagem'])) : ?>
                        <div class="col-md-12 col-sm-6 alert alert-danger text-center p-2 mt-3">
                            <?= $_SESSION['mensagem'] ?>
                            <?php unset($_SESSION['mensagem']) ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-12 my-3 text-center">


                        <button type="submit">Reservar</button>
                    </div>
                </form><!-- End Reservation Form -->
            <?php else: ?>

                <!-- <div id="calendar"></div> -->
                <form action="?a=reservar_mesa_submit" method="post" role="form" class="php-email-form">
                    <div class="row gy-4">
                        <div class="col-lg-4 col-md-6">
                            <input type="text" name="nome" class="form-control" value="<?= $dados_usuario->nome_completo ?>" placeholder="Seu Nome" required>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <input type="email" class="form-control" name="email" value="<?= $dados_usuario->email ?>" placeholder="Seu Email" required>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <input type="number" maxlength="13" pattern="\d*" class="form-control" name="telefone" value="<?= $dados_usuario->telefone ?>" placeholder="Seu Telefone" required>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <input type="number" class="form-control" name="pessoas" placeholder="Nº de Pessoa" required>
                        </div>

                        <div class="col-lg-4 col-md-6 morada_alternativa ">
                            <div class="m-0 p-0 php-email-form">
                                <select id="mealType" class="form-select" name="tipo_refeicao" required>
                                    <option value="">Tipo de Refeição</option>
                                    <option value="Café Manhã">Café Manhã</option>
                                    <option value="Almoço">Almoço</option>
                                    <option value="Jantar">Jantar</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">

                            <!-- Input do calendário -->
                            <input type="datetime-local" name="data_time" id="datePicker" class="form-control" required>
                        </div>

                    </div>
                    <div class="form-group mt-3">
                        <textarea class="form-control" name="mensagem" rows="5" placeholder="Message"></textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="aceitaTermos" required/>
                        <label class="form-check-label" for="aceitaTermos">
                            Eu li e concordo com os
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalTermos">Termos e Condições</a>.
                        </label>
                    </div>

                    <!-- Alerta para exibir a mensagem -->
                    <?php if (isset($_SESSION['mensagem'])) : ?>
                        <div class="col-md-12 col-sm-6 alert alert-danger text-center p-2 mt-3">
                            <?= $_SESSION['mensagem'] ?>
                            <?php unset($_SESSION['mensagem']) ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-12 my-3 text-center">


                        <button type="submit">Subtmeter</button>
                    </div>
                </form><!-- End Reservation Form -->
            <?php endif ?>
        </div>


    </section><!-- /Book A Table Section -->
</main>

<!-- Modal Termos e Condições -->
<div class="modal fade" id="modalTermos" tabindex="-1" aria-labelledby="modalTermosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable ">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTermosLabel">Termos e Condições de Reserva e Pedido Online</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p><strong>Última atualização:</strong> 16 de Maio de 2025</p>

                <h6>1. Aceitação dos Termos</h6>
                <p>Ao efetuar uma reserva ou pedido através deste sistema, o cliente declara que leu, compreendeu e concorda com os presentes Termos e Condições.</p>

                <h6>2. Processo de Reserva</h6>
                <ul>
                    <li>Todas as reservas estão sujeitas à disponibilidade.</li>
                    <li>A reserva só será considerada válida após a confirmação do pagamento.</li>
                    <li>O cliente deve fornecer informações corretas e atualizadas no momento da operaçõe.</li>
                </ul>

                <h6>3. Pagamento</h6>
                <ul>
                    <li>O pagamento é obrigatório no momento da reserva ou pedido.</li>
                    <li>O pagamento poder ser feito 24h depois da reserva ou pedido.</li>
                    <li>Métodos de pagamento aceitos: Transferência Bancária, Multicaixa Express, ou depositos.</li>
                    <li>É obrigatório que o cliente envie o comprovativo do pagamento.</li>
                    <li>O não pagamento dentro do prazo resultará no cancelamento automático da reserva.</li>
                </ul>

                <h6>4. Cancelamentos e Reembolsos</h6>
                <ul>
                    <li>Cancelamentos só serão aceites com pelo menos 2 horas de antecedência.</li>
                    <li>Não comparecimentos (“no-show”) não terão direito a reembolso.</li>
                    <li>Cancelamentos válidos poderão resultar em reembolso ou reagendamento, conforme avaliação da administração.</li>
                </ul>

                <h6>5. Responsabilidades do Cliente</h6>
                <ul>
                    <li>Chegar no horário indicado na reserva.</li>
                    <li>Apresentar o comprovativo de pagamento, se necessário.</li>
                    <li>Cumprir as normas e políticas do local de atendimento.</li>
                </ul>

                <h6>6. Alterações pela Administração</h6>
                <p>A administração reserva-se o direito de alterar ou cancelar reservas por motivos operacionais ou imprevistos. Nestes casos, o cliente será informado e poderá optar por reagendamento ou reembolso.</p>

                <h6>7. Proteção de Dados</h6>
                <p>Os dados fornecidos serão utilizados exclusivamente para fins de gestão da reserva/pedido, conforme a nossa <a href="?a=termos_condicoes">Política de Privacidade</a>.</p>

                <h6>8. Contato</h6>
                <p>Em caso de dúvidas ou problemas, entre em contato pelo:</p>
                <ul>
                    <li>Email: <a href="mailto:restaurantecervejariakissanga@gmail.com">teuemail@exemplo.com</a></li>
                    <li>WhatsApp: <a href="https://wa.me/244947856498">+244 947 856 498</a></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    const aceitaTermos = document.getElementById('aceitaTermos');
    const submitBtn = document.getElementById('submitBtn');

    aceitaTermos.addEventListener('change', () => {
        submitBtn.disabled = !aceitaTermos.checked;
    });

    document.getElementById('reservaForm').addEventListener('submit', e => {
        if (!aceitaTermos.checked) {
            e.preventDefault();
            alert('Por favor, aceite os Termos e Condições antes de enviar.');
        }
    });
</script>