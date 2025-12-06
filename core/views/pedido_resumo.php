<main class="main">

    <!-- Page Title -->
    <div class="page-title position-relative" data-aos="fade"
        style="background-image: url(assets/img/page-title-bg.webp);">

    </div><!-- End Page Title -->
    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

        <!-- Section Title -->
        <div class="container section-title py-2" data-aos="fade-up">
            <h2>Seu Pedido</h2>
            <p>Resumo<br></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">


            <div class="row">
                <div class="col">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-end">Valor total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $index = 0;
                            $total_rows = count($carrinho);

                            ?>
                            <?php foreach ($carrinho as $produto) : ?>
                                <?php if ($index < $total_rows - 1) : ?>

                                    <!-- lista dos produtos -->
                                    <tr class="">
                                        <td class="align-middle ">
                                            <h5 class=""><?= $produto['titulo']; ?></h5>
                                        </td>
                                        <td class="text-center align-middle">
                                            <h5><?= $produto['quantidade'] ?></h5>
                                        </td>
                                        <td class="text-end align-middle">
                                            <h4><?= number_format($produto['preco'], 2, ',', '.') ?></h4>
                                        </td>
                                    </tr>

                                <?php else : ?>

                                    <!-- total -->
                                    <!-- total -->
                                    <tr>
                                        <td></td>
                                        <td class="text-end">
                                            <h4>Total:</h4>
                                        </td>
                                        <td class="text-end">
                                            <h4><?= number_format($produto, 2, ',', '.') . 'kz' ?></h4>
                                        </td>
                                    </tr>

                                <?php endif; ?>
                                <?php $index++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- dados do cliente -->
                    <h5 class="bg-dark text-white p-2">Dados do Cliente</h5>
                    <div class="row">
                        <div class="col">
                            <p>Nome: <strong><?= $cliente->nome_completo ?></strong></p>
                            <p>Email: <strong><?= $cliente->email ?></strong></p>
                            <p>Telefone: <strong><?= $cliente->telefone ?></strong></p>
                        </div>
                    </div>

                    <!-- DADOS DE PAGAMENTO -->
                    <h5 class="bg-dark text-white p-2">Dados do Pagamento</h5>
                    <div class="row">
                        <div class="col">
                            <p>Conta bancária: 1234567890</p>
                            <p>Código do Pedido: <strong><?= $_SESSION['codigo_pedido'] ?></strong></p>
                            <p>Total: <strong><?= number_format($produto, 2, ',', '.') . 'kz' ?></strong></p>
                        </div>
                    </div>


                    <!-- Defina a modalidade do pedido [no local(dentro do restaurante) || em casa (entrega)] -->
                    <div class="section-title py-2" data-aos="fade-up">
                        <h2>Modalidade do Pedido</h2>
                        <div class="row my-3">

                            <div class="col-2 form-check ms-3">
                                <input class="form-check-input" onchange="pedido_local()" type="checkbox" name="check_pedido_local" id="check_pedido_local">
                                <label class="form-check-label" for="check_pedido_local">Na Hora.</label>
                            </div>

                            <div class="col-6 form-check">
                                <input class="form-check-input" onchange="pedido_fora()" type="checkbox" name="check_pedido_fora" id="check_pedido_fora">
                                <label class="form-check-label" for="check_pedido_fora">Encomenda.</label>
                            </div>

                            <div id="pedido_local" style="display: none;" class="morada_alternativa">
                                <div class="morada_alternativa">
                                    <span>Deve apresenta o comprovativo ao pessoal de atendimento</span>

                                    <div class="php-email-form mt-3">

                                        <!-- Numero da mesa -->
                                        <div class="mb-3 col-6">
                                            <label class="form-label">Numero da Mesa:</label>
                                            <!-- <input class="form-control" type="text" id="text_morada_alternativa"> -->


                                            <select class="form-control" name="text_mesa" id="text_mesa">
                                                <?php foreach ($mesas as $mesa): ?>
                                                    <option value="<?= $mesa->numero ?>"><?= $mesa->numero ?></option>
                                                <?php endforeach; ?>
                                            </select>


                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div id="pedido_fora" style="display: none;">
                                <!-- morada alternativa -->
                                <h5 class="bg-dark text-white p-2">Morada alternativa de entrega</h5>
                                <div class="form-check">
                                    <input class="form-check-input" onchange="usar_morada_alternativa()" type="checkbox" name="check_morada_alternativa" id="check_morada_alternativa">
                                    <label class="form-check-label" for="check_morada_alternativa">Definir uma morada alternativa.</label>
                                </div>


                                <!-- morada alternativa -->
                                <div id="morada_alternativa" style="display: none" class="morada_alternativa">

                                    <div class="php-email-form mt-3">

                                        <!-- morada -->
                                        <div class="mb-3">
                                            <label class="form-label">Morada:</label>
                                            <input class="form-control" type="text" id="text_morada_alternativa">

                                        </div>

                                        <!-- email -->
                                        <div class="mb-3">
                                            <label class="form-label">Email:</label>
                                            <input class="form-control" type="email" id="text_email_alternativo">
                                        </div>

                                        <!-- telefone -->
                                        <div class="mb-3">
                                            <label class="form-label">Telefone:</label>
                                            <input class="form-control" type="text" id="text_telefone_alternativo">
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- No local -->
                    <div class="morada_alternativa">

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="aceitaTermos" required />
                            <label class="form-check-label" for="aceitaTermos">
                                Eu li e concordo com os
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalTermos">Termos e Condições</a>.
                            </label>
                        </div>
                        <div class="php-email-form">


                            <div class="row my-3">
                                <div class="col">
                                    <a href="?a=carrinho" class="btn btn-primary">Cancelar</a>
                                </div>

                                <div class="col text-end" id="buttons" style="display: none;">
                                    <a href="?a=confirmar_pedido&m=local" id="submitBtn" onclick="morada_alternativa()" class="btn btn-primary">Confirmar Pedido</a>
                                </div>

                                <div class="col text-end" id="buttons_1" style="display: none;">
                                    <a href="?a=confirmar_pedido&m=fora" id="submitBtn" onclick="morada_alternativa()" class="btn btn-primary">Confirmar Pedido</a>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section><!-- /Starter Section Section -->

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

                <h6>2. Processo do Pedido</h6>
                <ul>
                    <li>Todos os pedidos estão sujeitas à disponibilidade.</li>
                    <li>O pedido só será considerada válida após a confirmação do pagamento.</li>
                    <li>O cliente deve fornecer informações corretas e atualizadas no momento da operaçõe.</li>
                </ul>

                <h6>3. Pagamento</h6>
                <ul>
                    <li>O pagamento é obrigatório no momento da reserva ou pedido.</li>
                    <li>O pagamento poder ser feito 2h depois da reserva ou pedido.</li>
                    <li>Métodos de pagamento aceitos: Transferência Bancária, Multicaixa Express, ou depositos.</li>
                    <li>É obrigatório que o cliente envie o comprovativo do pagamento.</li>
                    <li>O não pagamento dentro do prazo resultará no cancelamento automático da reserva.</li>
                </ul>

                <h6>4. Cancelamentos e Reembolsos</h6>
                <ul>
                    <li>Cancelamentos só serão aceites com pelo menos 24 horas de antecedência.</li>
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
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('aceitaTermos');
        const buttons = document.getElementById('buttons'); // Local
        const buttons1 = document.getElementById('buttons_1'); // Fora

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Aqui você escolhe qual botão mostrar

                var l = document.getElementById('check_pedido_local');
                var f = document.getElementById('check_pedido_fora');

                if (l.checked == true) {

                    // mostra o quadro para definir morada alternativa
                    buttons.style.display = 'block';

                } else if (f.checked == true) {
                    buttons1.style.display = 'block';

                } else {

                    buttons.style.display = 'none';
                    buttons1.style.display = 'none';
                }

                // ou buttons1.style.display = 'block';
            } else {
                buttons.style.display = 'none';
                buttons1.style.display = 'none';
            }
        });
    });
</script>